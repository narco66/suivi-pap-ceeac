<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide d'import Excel - SUIVI-PAPA CEEAC</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #003366;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #003366;
            margin: 10px 0;
            font-size: 24pt;
        }
        .header .subtitle {
            color: #666;
            font-size: 12pt;
            margin-top: 5px;
        }
        .meta {
            text-align: right;
            font-size: 9pt;
            color: #666;
            margin-bottom: 30px;
        }
        h2 {
            color: #003366;
            font-size: 16pt;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #003366;
            padding-bottom: 5px;
        }
        h3 {
            color: #004488;
            font-size: 13pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p {
            text-align: justify;
            margin-bottom: 12px;
        }
        ul, ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        li {
            margin-bottom: 8px;
        }
        .box {
            background-color: #f5f5f5;
            border-left: 4px solid #003366;
            padding: 15px;
            margin: 20px 0;
        }
        .box.warning {
            background-color: #fff3cd;
            border-left-color: #ffc107;
        }
        .box.info {
            background-color: #d1ecf1;
            border-left-color: #17a2b8;
        }
        .box.success {
            background-color: #d4edda;
            border-left-color: #28a745;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
        }
        table th {
            background-color: #003366;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 10pt;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Guide d'import Excel</h1>
        <div class="subtitle">Documentation complète pour l'import de données Excel</div>
        <div class="subtitle">SUIVI-PAPA CEEAC</div>
    </div>

    <div class="meta">
        <strong>Version :</strong> 1.2<br>
        <strong>Date :</strong> 20 février 2024<br>
        <strong>Plateforme :</strong> SUIVI-PAPA CEEAC
    </div>

    <h2>1. Introduction</h2>
    <p>
        Ce guide vous accompagne dans l'import de vos données Excel dans la plateforme SUIVI-PAPA CEEAC. 
        Il explique étape par étape comment préparer vos fichiers, les formater correctement et les importer 
        dans le système.
    </p>
    <p>
        La plateforme SUIVI-PAPA CEEAC permet l'import de Plans d'Action Prioritaires (PAPA) depuis des 
        fichiers Excel, facilitant ainsi la saisie en masse de données structurées.
    </p>

    <div class="box info">
        <strong>ℹ️ Information :</strong> Avant de commencer, assurez-vous d'avoir les droits d'import 
        dans la plateforme. Contactez votre administrateur si nécessaire.
    </div>

    <h2>2. Prérequis</h2>
    <p>Avant de procéder à l'import, vérifiez que vous disposez de :</p>
    <ul>
        <li>Un compte utilisateur actif avec les permissions d'import</li>
        <li>Un fichier Excel (.xlsx ou .xls) correctement formaté</li>
        <li>Les données à importer préparées selon le modèle fourni</li>
        <li>Une connexion Internet stable</li>
    </ul>

    <h2>3. Structure du fichier Excel</h2>
    <p>
        Le fichier Excel doit respecter une structure précise pour être importé correctement. 
        Voici les colonnes obligatoires et leur format :
    </p>

    <h3>3.1. Colonnes obligatoires</h3>
    <table>
        <thead>
            <tr>
                <th>Colonne</th>
                <th>Description</th>
                <th>Format</th>
                <th>Exemple</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>CODE_PAPA</code></td>
                <td>Code unique du PAPA</td>
                <td>Texte</td>
                <td>PAPA-2025</td>
            </tr>
            <tr>
                <td><code>LIBELLE_PAPA</code></td>
                <td>Libellé du Plan d'Action Prioritaire</td>
                <td>Texte</td>
                <td>Plan d'Action Prioritaire 2025</td>
            </tr>
            <tr>
                <td><code>ANNEE</code></td>
                <td>Année du PAPA</td>
                <td>Nombre</td>
                <td>2025</td>
            </tr>
            <tr>
                <td><code>CODE_OBJECTIF</code></td>
                <td>Code de l'objectif</td>
                <td>Texte</td>
                <td>OBJ-001</td>
            </tr>
            <tr>
                <td><code>LIBELLE_OBJECTIF</code></td>
                <td>Libellé de l'objectif</td>
                <td>Texte</td>
                <td>Renforcer l'intégration régionale</td>
            </tr>
            <tr>
                <td><code>CODE_ACTION</code></td>
                <td>Code de l'action prioritaire</td>
                <td>Texte</td>
                <td>ACT-001</td>
            </tr>
            <tr>
                <td><code>LIBELLE_ACTION</code></td>
                <td>Libellé de l'action prioritaire</td>
                <td>Texte</td>
                <td>Harmoniser les politiques commerciales</td>
            </tr>
            <tr>
                <td><code>CODE_TACHE</code></td>
                <td>Code de la tâche</td>
                <td>Texte</td>
                <td>TACHE-001</td>
            </tr>
            <tr>
                <td><code>LIBELLE_TACHE</code></td>
                <td>Libellé de la tâche</td>
                <td>Texte</td>
                <td>Élaborer un document de politique</td>
            </tr>
            <tr>
                <td><code>DATE_DEBUT_PREVUE</code></td>
                <td>Date de début prévue</td>
                <td>Date (JJ/MM/AAAA)</td>
                <td>01/03/2025</td>
            </tr>
            <tr>
                <td><code>DATE_FIN_PREVUE</code></td>
                <td>Date de fin prévue</td>
                <td>Date (JJ/MM/AAAA)</td>
                <td>31/12/2025</td>
            </tr>
            <tr>
                <td><code>STATUT</code></td>
                <td>Statut de la tâche</td>
                <td>Texte (planifiee, en_cours, achevee, en_attente, annulee)</td>
                <td>planifiee</td>
            </tr>
        </tbody>
    </table>

    <h3>3.2. Colonnes optionnelles</h3>
    <table>
        <thead>
            <tr>
                <th>Colonne</th>
                <th>Description</th>
                <th>Format</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>DESCRIPTION</code></td>
                <td>Description détaillée</td>
                <td>Texte</td>
            </tr>
            <tr>
                <td><code>PRIORITE</code></td>
                <td>Niveau de priorité (faible, moyenne, élevée, critique)</td>
                <td>Texte</td>
            </tr>
            <tr>
                <td><code>CRITICITE</code></td>
                <td>Niveau de criticité (faible, moyenne, élevée, critique)</td>
                <td>Texte</td>
            </tr>
            <tr>
                <td><code>RESPONSABLE</code></td>
                <td>Email du responsable</td>
                <td>Email</td>
            </tr>
            <tr>
                <td><code>POURCENTAGE_AVANCEMENT</code></td>
                <td>Pourcentage d'avancement (0-100)</td>
                <td>Nombre</td>
            </tr>
        </tbody>
    </table>

    <div class="box warning">
        <strong>⚠️ Attention :</strong> Les noms de colonnes sont sensibles à la casse et doivent correspondre 
        exactement à ceux indiqués dans ce guide. Utilisez le modèle Excel fourni pour éviter les erreurs.
    </div>

    <h2>4. Préparation du fichier</h2>
    
    <h3>4.1. Format du fichier</h3>
    <ul>
        <li>Format accepté : <code>.xlsx</code> ou <code>.xls</code></li>
        <li>Taille maximale : 10 Mo</li>
        <li>Première ligne : En-têtes des colonnes</li>
        <li>Lignes suivantes : Données à importer</li>
    </ul>

    <h3>4.2. Vérifications avant import</h3>
    <p>Avant d'importer votre fichier, vérifiez :</p>
    <ol>
        <li><strong>Cohérence des codes :</strong> Les codes doivent être uniques et respecter le format attendu</li>
        <li><strong>Dates valides :</strong> Les dates doivent être au format JJ/MM/AAAA et cohérentes (début &lt; fin)</li>
        <li><strong>Statuts valides :</strong> Utilisez uniquement les statuts autorisés</li>
        <li><strong>Hiérarchie :</strong> Respectez la hiérarchie PAPA → Objectif → Action → Tâche</li>
        <li><strong>Pas de cellules vides :</strong> Les colonnes obligatoires ne doivent pas être vides</li>
    </ol>

    <div class="box success">
        <strong>✓ Astuce :</strong> Utilisez la fonction de validation des données d'Excel pour vous assurer 
        que vos données respectent les formats attendus avant l'import.
    </div>

    <h2>5. Processus d'import</h2>
    
    <h3>5.1. Accéder à la fonctionnalité d'import</h3>
    <ol>
        <li>Connectez-vous à la plateforme SUIVI-PAPA CEEAC</li>
        <li>Accédez au menu "Import" ou "PAPA" → "Importer"</li>
        <li>Cliquez sur "Nouvel import" ou "Importer un fichier Excel"</li>
    </ol>

    <h3>5.2. Sélectionner le fichier</h3>
    <ol>
        <li>Cliquez sur "Parcourir" ou "Choisir un fichier"</li>
        <li>Sélectionnez votre fichier Excel préparé</li>
        <li>Vérifiez que le nom du fichier s'affiche correctement</li>
    </ol>

    <h3>5.3. Configurer l'import</h3>
    <p>Avant de lancer l'import, vous pouvez configurer :</p>
    <ul>
        <li><strong>Version PAPA :</strong> Sélectionnez la version du PAPA concernée</li>
        <li><strong>Mode d'import :</strong> Création uniquement ou mise à jour si existe</li>
        <li><strong>Gestion des erreurs :</strong> Arrêter à la première erreur ou continuer</li>
    </ul>

    <h3>5.4. Lancer l'import</h3>
    <ol>
        <li>Cliquez sur le bouton "Importer" ou "Valider"</li>
        <li>Attendez la fin du traitement (une barre de progression s'affiche)</li>
        <li>Consultez le rapport d'import généré</li>
    </ol>

    <div class="box info">
        <strong>ℹ️ Information :</strong> Le temps d'import dépend de la taille de votre fichier. 
        Pour les gros fichiers (&gt; 1000 lignes), le traitement peut prendre plusieurs minutes.
    </div>

    <h2>6. Rapport d'import</h2>
    <p>
        Après chaque import, un rapport détaillé est généré. Il contient :
    </p>
    <ul>
        <li><strong>Statistiques globales :</strong> Nombre de lignes traitées, créées, mises à jour, rejetées</li>
        <li><strong>Erreurs :</strong> Liste détaillée des erreurs rencontrées avec numéro de ligne</li>
        <li><strong>Avertissements :</strong> Informations sur les données qui ont nécessité des corrections automatiques</li>
        <li><strong>Succès :</strong> Confirmation des données importées correctement</li>
    </ul>

    <h3>6.1. Types d'erreurs courantes</h3>
    <table>
        <thead>
            <tr>
                <th>Type d'erreur</th>
                <th>Description</th>
                <th>Solution</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Colonne manquante</td>
                <td>Une colonne obligatoire est absente</td>
                <td>Ajoutez la colonne manquante avec le nom exact</td>
            </tr>
            <tr>
                <td>Format de date invalide</td>
                <td>La date n'est pas au format JJ/MM/AAAA</td>
                <td>Corrigez le format des dates dans Excel</td>
            </tr>
            <tr>
                <td>Code dupliqué</td>
                <td>Un code existe déjà dans la base</td>
                <td>Vérifiez les codes existants ou modifiez les codes dupliqués</td>
            </tr>
            <tr>
                <td>Statut invalide</td>
                <td>Le statut n'est pas dans la liste autorisée</td>
                <td>Utilisez un statut valide : planifiee, en_cours, achevee, en_attente, annulee</td>
            </tr>
            <tr>
                <td>Date incohérente</td>
                <td>La date de fin est antérieure à la date de début</td>
                <td>Corrigez les dates pour respecter la logique temporelle</td>
            </tr>
        </tbody>
    </table>

    <h2>7. Bonnes pratiques</h2>
    
    <h3>7.1. Avant l'import</h3>
    <ul>
        <li>Testez d'abord avec un petit échantillon de données</li>
        <li>Vérifiez la cohérence des données dans Excel</li>
        <li>Faites une sauvegarde de vos données avant import</li>
        <li>Utilisez le modèle Excel fourni par la plateforme</li>
    </ul>

    <h3>7.2. Pendant l'import</h3>
    <ul>
        <li>Ne fermez pas la page pendant l'import</li>
        <li>Attendez la fin du traitement avant de naviguer ailleurs</li>
        <li>Notez le numéro de référence de l'import pour suivi</li>
    </ul>

    <h3>7.3. Après l'import</h3>
    <ul>
        <li>Consultez toujours le rapport d'import</li>
        <li>Vérifiez les données importées dans la plateforme</li>
        <li>Corrigez les erreurs et réimportez si nécessaire</li>
        <li>Conservez le rapport d'import pour référence</li>
    </ul>

    <h2>8. Dépannage</h2>
    
    <h3>8.1. Problèmes fréquents</h3>
    
    <p><strong>Le fichier n'est pas reconnu :</strong></p>
    <ul>
        <li>Vérifiez que le format est .xlsx ou .xls</li>
        <li>Assurez-vous que le fichier n'est pas corrompu</li>
        <li>Essayez de le rouvrir dans Excel et le sauvegarder à nouveau</li>
    </ul>

    <p><strong>L'import échoue systématiquement :</strong></p>
    <ul>
        <li>Vérifiez que toutes les colonnes obligatoires sont présentes</li>
        <li>Contrôlez que les noms de colonnes sont exacts (sensible à la casse)</li>
        <li>Vérifiez que vous avez les permissions d'import</li>
    </ul>

    <p><strong>Certaines lignes ne sont pas importées :</strong></p>
    <ul>
        <li>Consultez le rapport d'import pour identifier les erreurs</li>
        <li>Corrigez les données dans Excel et réimportez uniquement les lignes concernées</li>
    </ul>

    <h2>9. Support et assistance</h2>
    <p>
        Pour toute question ou problème lié à l'import de données :
    </p>
    <ul>
        <li><strong>Documentation :</strong> Consultez la section "Documentation" de la plateforme</li>
        <li><strong>Support technique :</strong> Contactez l'équipe DSI à dsi@ceeac.int</li>
        <li><strong>Formation :</strong> Des sessions de formation peuvent être organisées sur demande</li>
    </ul>

    <div class="box success">
        <strong>✓ Rappel :</strong> Ce guide est régulièrement mis à jour. Assurez-vous d'utiliser 
        la dernière version disponible sur la plateforme.
    </div>

    <div class="footer">
        <p>
            <strong>SUIVI-PAPA CEEAC</strong><br>
            Communauté Économique des États de l'Afrique Centrale<br>
            Version 1.2 - Février 2024<br>
            <em>Document confidentiel - Usage interne uniquement</em>
        </p>
    </div>
</body>
</html>


