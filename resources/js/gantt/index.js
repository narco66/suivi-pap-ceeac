/**
 * Module Gantt - Phase 1 MVP
 * Initialisation et gestion du diagramme de Gantt avec Frappe Gantt
 */

document.addEventListener('DOMContentLoaded', function() {
    let gantt = null;
    const container = document.getElementById('gantt-container');
    
    if (!container) {
        console.error('Conteneur Gantt introuvable');
        return;
    }

    // Vérifier que Frappe Gantt est chargé
    if (typeof Gantt === 'undefined') {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                <p class="mt-3 text-danger">La bibliothèque Frappe Gantt n'est pas chargée. Veuillez recharger la page.</p>
            </div>
        `;
        return;
    }

    // Fonction pour charger les données Gantt
    window.loadGanttData = function() {
        if (container.dataset.loading === 'true') {
            return;
        }

        container.dataset.loading = 'true';
        
        const form = document.getElementById('ganttFilters');
        if (!form) {
            console.error('Formulaire de filtres introuvable');
            container.dataset.loading = 'false';
            return;
        }

        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        // Afficher le loader
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3 text-muted">Chargement du diagramme de Gantt...</p>
            </div>
        `;

        // Construire l'URL de l'API
        const papaId = formData.get('papa_id') || GANTT_CONFIG.selectedPapaId;
        if (!papaId) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    <p class="mt-3 text-warning">Veuillez sélectionner un PAPA pour afficher le diagramme.</p>
                </div>
            `;
            container.dataset.loading = 'false';
            return;
        }
        
        // Construire l'URL de l'API avec le PAPA sélectionné
        const apiUrl = `/api/projects/${papaId}/gantt` + (params.toString() ? '?' + params.toString() : '');

        // Récupérer les données
        fetch(apiUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            }
        })
        .then(response => {
            container.dataset.loading = 'false';
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const tasks = Array.isArray(data.data) ? data.data : [];
            const meta = data.meta || {};

            if (tasks.length > 0) {
                renderGantt(tasks, meta);
            } else {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="mt-3 text-muted">Aucune donnée disponible pour les filtres sélectionnés.</p>
                        <p class="text-muted small">Vérifiez que les tâches ont des dates de début et de fin prévues.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            container.dataset.loading = 'false';
            console.error('Erreur lors du chargement des données:', error);
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                    <p class="mt-3 text-danger">Erreur lors du chargement des données: ${error.message}</p>
                    <button class="btn btn-primary mt-3" onclick="window.loadGanttData()">Réessayer</button>
                </div>
            `;
        });
    };

    // Fonction pour rendre le Gantt
    function renderGantt(tasks, meta) {
        if (!tasks || tasks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Aucune tâche à afficher.</p>
                </div>
            `;
            return;
        }

        // Organiser les tâches par hiérarchie (phases → sous-tâches)
        const tasksMap = new Map();
        const rootTasks = [];
        
        // Créer une map de toutes les tâches
        tasks.forEach(task => {
            tasksMap.set(task.id.toString(), task);
        });
        
        // Organiser en hiérarchie
        tasks.forEach(task => {
            if (!task.parent || task.parent === '0' || !tasksMap.has(task.parent)) {
                rootTasks.push(task);
            }
        });
        
        // Fonction récursive pour organiser les sous-tâches
        function organizeHierarchy(parentId, level = 0) {
            const children = tasks.filter(t => t.parent === parentId);
            return children.map(child => {
                const subTasks = organizeHierarchy(child.id, level + 1);
                return { task: child, level, subTasks };
            });
        }
        
        // Convertir les données au format Frappe Gantt avec hiérarchie
        function convertTaskToGantt(task, level = 0) {
            const startDate = new Date(task.start);
            if (isNaN(startDate.getTime())) {
                console.warn('Date invalide pour la tâche:', task.id, task.start);
                return null;
            }

            let endDate;
            if (task.type === 'milestone') {
                endDate = new Date(startDate);
            } else {
                endDate = new Date(startDate);
                const duration = parseInt(task.duration) || 1;
                endDate.setDate(endDate.getDate() + duration);
            }

            // Préfixer le nom avec indentation pour la hiérarchie
            const indent = '  '.repeat(level);
            const taskName = level > 0 ? indent + '└─ ' + task.name : task.name;

            const taskData = {
                id: task.id.toString(),
                name: taskName || 'Tâche sans nom',
                start: startDate.toISOString().split('T')[0],
                end: endDate.toISOString().split('T')[0],
                progress: Math.round((parseFloat(task.progress) || 0) * 100),
                custom_class: getCustomClass(task),
            };

            // Ajouter les dépendances (format Frappe Gantt : "id1,id2,id3")
            if (task.dependencies && Array.isArray(task.dependencies) && task.dependencies.length > 0) {
                const deps = task.dependencies
                    .filter(dep => dep && dep.toString())
                    .map(dep => dep.toString());
                
                if (deps.length > 0) {
                    taskData.dependencies = deps.join(',');
                }
            }

            // Ajouter la couleur
            if (task.color) {
                taskData.custom_class += ' ' + getColorClass(task.color);
            }
            
            // Ajouter le parent pour la hiérarchie (si supporté par Frappe Gantt)
            if (task.parent && task.parent !== '0') {
                taskData.parent = task.parent;
            }

            return taskData;
        }
        
        // Convertir toutes les tâches en respectant la hiérarchie
        const ganttTasks = [];
        
        function addTaskWithChildren(task, level = 0) {
            const ganttTask = convertTaskToGantt(task, level);
            if (ganttTask) {
                ganttTasks.push(ganttTask);
            }
            
            // Ajouter les sous-tâches
            const children = tasks.filter(t => t.parent === task.id.toString());
            children.forEach(child => {
                addTaskWithChildren(child, level + 1);
            });
        }
        
        // Ajouter toutes les tâches racines et leurs enfants
        rootTasks.forEach(rootTask => {
            addTaskWithChildren(rootTask, 0);
        });
        
        // Si aucune tâche racine, ajouter toutes les tâches
        if (ganttTasks.length === 0) {
            tasks.forEach(task => {
                const ganttTask = convertTaskToGantt(task, 0);
                if (ganttTask) {
                    ganttTasks.push(ganttTask);
                }
            });
        }
        
        // Filtrer les tâches valides
        const filteredTasks = ganttTasks.filter(task => task !== null);

        if (filteredTasks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    <p class="mt-3 text-warning">Aucune tâche valide à afficher. Vérifiez les dates des tâches.</p>
                </div>
            `;
            return;
        }

        // Vider le conteneur
        container.innerHTML = '';

        // Créer le Gantt
        try {
            if (gantt && typeof gantt.destroy === 'function') {
                try {
                    gantt.destroy();
                } catch (e) {
                    console.warn('Erreur lors de la destruction du Gantt précédent:', e);
                }
            }

            // Déterminer la vue par défaut
            const defaultView = 'Month';
            
            gantt = new Gantt(container, filteredTasks, {
                header_height: 50,
                column_width: 30,
                step: 24,
                view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
                view_mode: defaultView,
                bar_height: 20,
                bar_corner_radius: 4,
                arrow_curve: 5,
                padding: 18,
                date_format: 'YYYY-MM-DD',
                language: 'fr',
                on_click: function(task) {
                    console.log('Tâche cliquée:', task);
                    // Afficher les détails de la tâche (Phase 2)
                },
                on_date_change: function(task, start, end) {
                    if (GANTT_CONFIG.editable) {
                        console.log('Date modifiée:', task, start, end);
                        // Phase 2: Synchronisation avec le backend
                        syncTaskDates(task, start, end);
                    }
                },
                on_progress_change: function(task, progress) {
                    if (GANTT_CONFIG.editable) {
                        console.log('Progression modifiée:', task, progress);
                        // Phase 2: Synchronisation avec le backend
                        syncTaskProgress(task, progress);
                    }
                },
                on_view_change: function(mode) {
                    console.log('Mode de vue changé:', mode);
                    // Mettre à jour les boutons de vue
                    updateViewButtons(mode);
                }
            });
            
            console.log('Gantt créé avec succès, nombre de tâches:', filteredTasks.length);
            
            // Initialiser les contrôles de vue après un court délai pour s'assurer que le DOM est prêt
            setTimeout(() => {
                initializeViewControls();
                // Appliquer les styles personnalisés après le rendu
                applyCustomStyles(tasks);
            }, 200);
        } catch (error) {
            console.error('Erreur lors de la création du Gantt:', error);
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                    <p class="mt-3 text-danger">Erreur lors de la création du diagramme: ${error.message}</p>
                </div>
            `;
        }
    }

    // Fonction pour appliquer les styles personnalisés selon le type
    function applyCustomStyles(tasks) {
        tasks.forEach(task => {
            const taskElement = container.querySelector(`[data-id="${task.id}"] .bar-wrapper .bar`);
            if (!taskElement) return;

            // Appliquer la couleur selon le type
            if (task.type === 'phase') {
                taskElement.setAttribute('fill', '#1e40af');
                taskElement.setAttribute('stroke', '#1e3a8a');
                taskElement.setAttribute('stroke-width', '2');
            } else if (task.type === 'milestone') {
                taskElement.setAttribute('fill', '#7c3aed');
                taskElement.setAttribute('rx', '50%');
                taskElement.setAttribute('ry', '50%');
            } else {
                // Tâche normale - utiliser la couleur de la tâche ou par défaut
                if (task.color) {
                    taskElement.setAttribute('fill', task.color);
                }
            }

            // Appliquer le style critique si nécessaire
            if (task.critical) {
                taskElement.setAttribute('stroke', '#dc3545');
                taskElement.setAttribute('stroke-width', '2');
                taskElement.setAttribute('stroke-dasharray', '5,5');
            }
        });
    }

    // Fonction pour obtenir la classe CSS personnalisée
    function getCustomClass(task) {
        const classes = [];
        
        if (task.type === 'phase') {
            classes.push('gantt-phase');
        } else if (task.type === 'milestone') {
            classes.push('gantt-milestone');
        }
        
        if (task.critical) {
            classes.push('gantt-critical');
        }
        
        return classes.join(' ');
    }

    // Fonction pour obtenir la classe de couleur
    function getColorClass(color) {
        if (color === '#dc3545') return 'gantt-critique';
        if (color === '#ffc107') return 'gantt-vigilance';
        return 'gantt-normal';
    }

    // Gestion du changement de PAPA pour mettre à jour les versions
    const papaSelect = document.getElementById('papa_id');
    if (papaSelect) {
        papaSelect.addEventListener('change', function() {
            const papaId = this.value;
            const versionSelect = document.getElementById('version_id');
            
            if (papaId && GANTT_CONFIG.papasData[papaId]) {
                const selectedPapa = GANTT_CONFIG.papasData[papaId];
                versionSelect.innerHTML = '<option value="">Toutes les versions</option>';
                selectedPapa.versions.forEach(version => {
                    versionSelect.innerHTML += `<option value="${version.id}">${version.libelle}</option>`;
                });
            } else {
                versionSelect.innerHTML = '<option value="">Toutes les versions</option>';
            }
        });
    }

    // Gestion de la soumission du formulaire
    const formElement = document.getElementById('ganttFilters');
    if (formElement) {
        formElement.addEventListener('submit', function(e) {
            e.preventDefault();
            window.loadGanttData();
        });
    }

    // Fonction pour initialiser les contrôles de vue
    function initializeViewControls() {
        console.log('Initialisation des contrôles de vue...');
        
        // Boutons de vue
        const viewButtons = document.querySelectorAll('#ganttViewModes button');
        console.log('Boutons de vue trouvés:', viewButtons.length);
        
        viewButtons.forEach(btn => {
            // Retirer les anciens event listeners si présents
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const viewMode = this.dataset.view;
                console.log('Changement de mode de vue:', viewMode);
                if (gantt) {
                    try {
                        gantt.change_view_mode(viewMode);
                        updateViewButtons(viewMode);
                    } catch (error) {
                        console.error('Erreur lors du changement de mode:', error);
                    }
                }
            });
        });

        // Boutons zoom
        const zoomInBtn = document.getElementById('ganttZoomIn');
        const zoomOutBtn = document.getElementById('ganttZoomOut');
        const fitBtn = document.getElementById('ganttFit');

        console.log('Boutons zoom:', {
            zoomIn: !!zoomInBtn,
            zoomOut: !!zoomOutBtn,
            fit: !!fitBtn
        });

        if (zoomInBtn) {
            // Retirer les anciens event listeners
            const newZoomIn = zoomInBtn.cloneNode(true);
            zoomInBtn.parentNode.replaceChild(newZoomIn, zoomInBtn);
            
            newZoomIn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Zoom avant cliqué');
                if (gantt) {
                    try {
                        // Zoom avant : augmenter la largeur des colonnes
                        const currentWidth = gantt.options.column_width || 30;
                        const newWidth = Math.min(100, currentWidth + 5); // Max 100px
                        
                        gantt.options.column_width = newWidth;
                        gantt.refresh();
                        
                        console.log('Zoom avant - Largeur colonne:', newWidth);
                    } catch (error) {
                        console.error('Erreur lors du zoom avant:', error);
                    }
                } else {
                    console.warn('Gantt non initialisé');
                }
            });
        } else {
            console.warn('Bouton zoom avant introuvable');
        }

        if (zoomOutBtn) {
            // Retirer les anciens event listeners
            const newZoomOut = zoomOutBtn.cloneNode(true);
            zoomOutBtn.parentNode.replaceChild(newZoomOut, zoomOutBtn);
            
            newZoomOut.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Zoom arrière cliqué');
                if (gantt) {
                    try {
                        // Zoom arrière : réduire la largeur des colonnes
                        const currentWidth = gantt.options.column_width || 30;
                        const newWidth = Math.max(15, currentWidth - 5); // Min 15px
                        
                        gantt.options.column_width = newWidth;
                        gantt.refresh();
                        
                        console.log('Zoom arrière - Largeur colonne:', newWidth);
                    } catch (error) {
                        console.error('Erreur lors du zoom arrière:', error);
                    }
                } else {
                    console.warn('Gantt non initialisé');
                }
            });
        } else {
            console.warn('Bouton zoom arrière introuvable');
        }

        if (fitBtn) {
            // Retirer les anciens event listeners
            const newFit = fitBtn.cloneNode(true);
            fitBtn.parentNode.replaceChild(newFit, fitBtn);
            
            newFit.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Ajustement à l\'écran cliqué');
                if (gantt) {
                    try {
                        // Réinitialiser la largeur des colonnes à la valeur par défaut
                        gantt.options.column_width = 30;
                        gantt.refresh();
                        
                        console.log('Ajustement à l\'écran - Largeur colonne réinitialisée');
                    } catch (error) {
                        console.error('Erreur lors de l\'ajustement:', error);
                    }
                } else {
                    console.warn('Gantt non initialisé');
                }
            });
        } else {
            console.warn('Bouton ajustement introuvable');
        }
        
        console.log('Contrôles de vue initialisés');
    }

    // Fonction pour mettre à jour les boutons de vue
    function updateViewButtons(activeMode) {
        const viewButtons = document.querySelectorAll('#ganttViewModes button');
        viewButtons.forEach(btn => {
            if (btn.dataset.view === activeMode) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Fonction pour synchroniser les dates (Phase 2)
    function syncTaskDates(task, start, end) {
        // TODO: Implémenter la synchronisation avec le backend
        console.log('Synchronisation des dates:', task.id, start, end);
    }

    // Fonction pour synchroniser la progression (Phase 2)
    function syncTaskProgress(task, progress) {
        // TODO: Implémenter la synchronisation avec le backend
        console.log('Synchronisation de la progression:', task.id, progress);
    }

    // Charger les données initiales
    window.loadGanttData();
});

