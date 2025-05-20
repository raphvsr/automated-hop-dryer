#!/usr/bin/env python3
"""
Journal d'activité Git pour projet universitaire
Génère un rapport détaillé des activités quotidiennes basé sur les commits
Auteur du rapport: Raphael Vasseur
"""

import subprocess
import datetime
import os
import re
import sys
from collections import defaultdict

def run_command(cmd, shell=True):
    """Exécute une commande et renvoie la sortie"""
    try:
        if shell:
            # Utilisez shell=True sur Windows pour une meilleure compatibilité
            output = subprocess.check_output(cmd, shell=True, stderr=subprocess.STDOUT, text=True)
        else:
            # Sur Linux/Mac, utilisez liste d'arguments sans shell
            output = subprocess.check_output(cmd.split(), stderr=subprocess.STDOUT, text=True)
        return output.strip()
    except subprocess.CalledProcessError as e:
        print(f"Erreur lors de l'exécution de la commande: {cmd}")
        print(f"Sortie d'erreur: {e.output}")
        return None

def check_git_repo():
    """Vérifie si le répertoire courant est un dépôt Git valide"""
    is_windows = sys.platform.startswith('win')
    try:
        cmd = "git rev-parse --is-inside-work-tree"
        result = run_command(cmd, shell=is_windows)
        return result == "true"
    except Exception as e:
        print(f"Erreur lors de la vérification du dépôt Git: {e}")
        return False

def get_repo_creation_date():
    """Récupère la date du premier commit du dépôt"""
    is_windows = sys.platform.startswith('win')
    try:
        cmd = 'git log --reverse --format="%cd" --date=iso | head -1'
        if is_windows:
            # Sur Windows, utilisez une commande PowerShell équivalente
            cmd = 'git log --reverse --format="%cd" --date=iso | Select-Object -First 1'
        
        date_str = run_command(cmd, shell=True)
        if date_str:
            # Gérer les différents formats de date possibles
            try:
                return datetime.datetime.strptime(date_str.split()[0], "%Y-%m-%d")
            except:
                try:
                    return datetime.datetime.strptime(date_str.split()[0], "%Y/%m/%d")
                except:
                    print(f"Format de date non reconnu: {date_str}")
                    return None
    except Exception as e:
        print(f"Erreur lors de la récupération de la date de création: {e}")
    return None

def get_all_commits():
    """Récupère tous les commits depuis le début du dépôt"""
    is_windows = sys.platform.startswith('win')
    try:
        # Tentative de récupération simple
        cmd = 'git log --pretty=format:"%h|%ad|%s" --date=short'
        output = run_command(cmd, shell=True)
        
        if not output:
            print("Aucune sortie pour la commande git log. Tentative alternative...")
            # Essayer une approche plus basique
            cmd = 'git log'
            output = run_command(cmd, shell=True)
            print(f"Résultat de git log: {'Données trouvées' if output else 'Aucune donnée'}")
            
            if output:
                # Analyser manuellement la sortie
                commits = []
                hash_pattern = re.compile(r'^commit\s+([0-9a-f]+)')
                date_pattern = re.compile(r'^Date:\s+(.+)')
                
                current_commit = None
                current_date = None
                current_message = None
                
                lines = output.split('\n')
                for line in lines:
                    hash_match = hash_pattern.match(line)
                    if hash_match:
                        # Sauvegarder le commit précédent s'il existe
                        if current_commit and current_date and current_message:
                            commits.append({
                                'hash': current_commit,
                                'date': current_date,
                                'author': "p0ubelle",
                                'message': current_message
                            })
                        
                        # Nouveau commit
                        current_commit = hash_match.group(1)[:7]  # Format court
                        current_date = None
                        current_message = None
                        continue
                    
                    date_match = date_pattern.match(line)
                    if date_match and current_commit:
                        try:
                            # Conversion de la date au format YYYY-MM-DD
                            date_obj = datetime.datetime.strptime(date_match.group(1), "%a %b %d %H:%M:%S %Y %z")
                            current_date = date_obj.strftime("%Y-%m-%d")
                        except:
                            current_date = date_match.group(1)
                        continue
                    
                    # La première ligne non vide après date est le message
                    if current_commit and current_date and not current_message and line.strip() and not line.startswith('    '):
                        current_message = line.strip()
                
                # Ajouter le dernier commit
                if current_commit and current_date and current_message:
                    commits.append({
                        'hash': current_commit,
                        'date': current_date,
                        'author': "p0ubelle",
                        'message': current_message
                    })
                
                return [c for c in commits if not c['message'].startswith("Merge branch")]
        else:
            # Traitement normal
            commits = []
            for line in output.split('\n'):
                if line.strip():
                    parts = line.split('|')
                    if len(parts) >= 3:
                        # Ignorer les commits de fusion
                        if not parts[2].startswith("Merge branch"):
                            commits.append({
                                'hash': parts[0],
                                'date': parts[1],
                                'author': "p0ubelle",
                                'message': parts[2]
                            })
            return commits
            
    except Exception as e:
        print(f"Erreur lors de la récupération des commits: {e}")
        return []

def get_commit_details(commit_hash):
    """Récupère les détails complets d'un commit (fichiers modifiés)"""
    is_windows = sys.platform.startswith('win')
    try:
        # Récupérer les fichiers modifiés
        cmd = f'git show --name-only --pretty=format:"" {commit_hash}'
        files_output = run_command(cmd, shell=True)
        files = [f for f in files_output.split('\n') if f.strip()]
        
        # Récupérer les statistiques
        cmd = f'git show --stat {commit_hash}'
        stats_output = run_command(cmd, shell=True)
        
        stats_summary = ""
        if stats_output:
            # Recherche les statistiques de modification
            for line in stats_output.split('\n'):
                if 'file' in line and ('changed' in line or 'insertion' in line or 'deletion' in line):
                    stats_summary = line.strip()
                    break
        
        # Récupérer la description du commit
        cmd = f'git show -s --format="%B" {commit_hash}'
        description = run_command(cmd, shell=True)
        
        return {
            'files': files,
            'stats': stats_summary,
            'description': description or commit_hash
        }
    except Exception as e:
        print(f"Erreur lors de la récupération des détails du commit {commit_hash}: {e}")
        return {'files': [], 'stats': "", 'description': ""}

def get_file_type_categories(file_path):
    """Catégorise un fichier selon son extension et chemin"""
    extension = os.path.splitext(file_path)[1].lower()
    
    if not extension and os.path.basename(file_path).lower() in ['makefile', 'dockerfile']:
        return "Configuration"
        
    categories = {
        '.py': "Code Python",
        '.java': "Code Java",
        '.js': "JavaScript",
        '.html': "HTML",
        '.css': "CSS",
        '.c': "Code C",
        '.cpp': "Code C++",
        '.h': "En-têtes C/C++",
        '.sql': "Base de données",
        '.php': "PHP",
        '.md': "Documentation",
        '.txt': "Documentation",
        '.json': "Configuration",
        '.xml': "Configuration",
        '.yml': "Configuration",
        '.yaml': "Configuration",
        '.ini': "Configuration",
        '.cfg': "Configuration",
        '.conf': "Configuration",
        '.pdf': "Document",
        '.doc': "Document",
        '.docx': "Document",
        '.xls': "Document",
        '.xlsx': "Document",
        '.ppt': "Document",
        '.pptx': "Document",
        '.csv': "Données",
        '.dat': "Données",
        '.jpg': "Media",
        '.jpeg': "Media",
        '.png': "Media",
        '.gif': "Media",
        '.mp3': "Media",
        '.mp4': "Media",
        '.svg': "Media"
    }
    
    if extension in categories:
        return categories[extension]
    
    if '/test/' in file_path or '_test' in file_path:
        return "Tests"
        
    if '/docs/' in file_path or '/documentation/' in file_path:
        return "Documentation"
        
    return "Autre"

def generate_daily_activity_report(commits):
    """Génère un rapport d'activité quotidien basé sur les commits"""
    # Organiser les commits par date
    commits_by_date = defaultdict(list)
    for commit in commits:
        commits_by_date[commit['date']].append(commit)
    
    # Trier les dates dans l'ordre chronologique
    sorted_dates = sorted(commits_by_date.keys())
    
    report = []
    report.append("\n" + "="*80)
    report.append("                     JOURNAL D'ACTIVITÉ DU PROJET")
    report.append("                     Auteur: Raphael Vasseur")
    report.append("="*80 + "\n")
    
    for date in sorted_dates:
        daily_commits = commits_by_date[date]
        
        day_report = []
        day_report.append(f"\n\n## {date} ({len(daily_commits)} commits)")
        day_report.append("----------------------------\n")
        
        # Récupérer tous les fichiers modifiés ce jour-là
        files_modified = set()
        categories_worked = defaultdict(int)
        total_insertions = 0
        total_deletions = 0
        
        for commit in daily_commits:
            details = get_commit_details(commit['hash'])
            
            # Analyser les fichiers modifiés
            for file in details['files']:
                files_modified.add(file)
                category = get_file_type_categories(file)
                categories_worked[category] += 1
            
            # Extraire les statistiques d'insertion/suppression
            stats = details['stats']
            insertions_match = re.search(r'(\d+) insertion', stats) if stats else None
            deletions_match = re.search(r'(\d+) deletion', stats) if stats else None
            
            if insertions_match:
                total_insertions += int(insertions_match.group(1))
            if deletions_match:
                total_deletions += int(deletions_match.group(1))
            
            # Ajouter le détail du commit
            day_report.append(f"### {commit['message']}")
            day_report.append(f"- Hash: {commit['hash']}")
            day_report.append(f"- Auteur: Raphael Vasseur")
            day_report.append(f"- Description: {details['description'][:100]}..." if len(details['description']) > 100 else f"- Description: {details['description']}")
            
            # Lister les fichiers modifiés (limité à 5 pour la lisibilité)
            if details['files']:
                day_report.append("- Fichiers modifiés:")
                for file in details['files'][:5]:
                    day_report.append(f"  * {file}")
                if len(details['files']) > 5:
                    day_report.append(f"  * ... et {len(details['files']) - 5} autres fichiers")
            
            if stats:
                day_report.append(f"- Changements: {stats}\n")
            else:
                day_report.append("- Changements: Non disponibles\n")
        
        # Résumé de la journée
        summary = []
        summary.append("\n### Résumé de la journée:")
        summary.append(f"- Total fichiers modifiés: {len(files_modified)}")
        summary.append(f"- Lignes ajoutées: {total_insertions}")
        summary.append(f"- Lignes supprimées: {total_deletions}")
        summary.append(f"- Bilan lignes: {total_insertions - total_deletions:+}")
        
        summary.append("\n#### Catégories de travail:")
        for category, count in sorted(categories_worked.items(), key=lambda x: x[1], reverse=True):
            summary.append(f"- {category}: {count} fichiers")
        
        # Ajouter le résumé au début du rapport journalier
        day_report = day_report[:1] + summary + day_report[1:]
        
        report.extend(day_report)
    
    return "\n".join(report)

def generate_global_summary(commits):
    """Génère un résumé global du projet"""
    summary = []
    summary.append("\n" + "="*80)
    summary.append("                     RÉSUMÉ GLOBAL DU PROJET")
    summary.append("                     Auteur: Raphael Vasseur")
    summary.append("="*80 + "\n")
    
    # Statistiques générales
    creation_date = get_repo_creation_date()
    today = datetime.datetime.now().date()
    
    if creation_date:
        days_since_creation = (today - creation_date.date()).days
        summary.append(f"Date de début du projet: {creation_date.strftime('%Y-%m-%d')}")
        summary.append(f"Durée du projet: {days_since_creation} jours")
    
    summary.append(f"Nombre total de commits: {len(commits)}")
    
    if creation_date and days_since_creation > 0:
        avg_commits = len(commits) / days_since_creation
        summary.append(f"Moyenne de commits par jour: {avg_commits:.2f}")
    
    # Commits par mois
    commits_by_month = defaultdict(int)
    for commit in commits:
        # Gérer le cas où la date pourrait ne pas être au format attendu
        try:
            month = commit['date'][:7]  # YYYY-MM
            commits_by_month[month] += 1
        except:
            print(f"Format de date non standard: {commit['date']}")
    
    if commits_by_month:
        summary.append("\n### Activité par mois:")
        for month, count in sorted(commits_by_month.items()):
            summary.append(f"- {month}: {count} commits")
    
    # Analyser toutes les catégories travaillées
    all_files = set()
    categories = defaultdict(int)
    
    for commit in commits:
        details = get_commit_details(commit['hash'])
        for file in details['files']:
            all_files.add(file)
            category = get_file_type_categories(file)
            categories[category] += 1
    
    if categories:
        summary.append("\n### Répartition du travail par catégorie:")
        for category, count in sorted(categories.items(), key=lambda x: x[1], reverse=True):
            percentage = (count / sum(categories.values())) * 100
            summary.append(f"- {category}: {count} fichiers ({percentage:.1f}%)")
    
    return "\n".join(summary)

def save_report_to_file(content, filename):
    """Sauvegarde le rapport dans un fichier"""
    try:
        with open(filename, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Rapport sauvegardé dans {filename}")
    except Exception as e:
        print(f"Erreur lors de la sauvegarde du rapport: {e}")

def main():
    import argparse
    
    parser = argparse.ArgumentParser(description="Générateur de journal d'activité Git pour projet universitaire")
    parser.add_argument("--path", help="Chemin vers le dépôt Git (défaut: répertoire courant)")
    parser.add_argument("--output", default="journal_activite_git.md", help="Nom du fichier de sortie (défaut: journal_activite_git.md)")
    parser.add_argument("--debug", action="store_true", help="Afficher les informations de débogage")
    
    args = parser.parse_args()
    
    if args.path:
        os.chdir(args.path)
    
    # Vérification du dépôt Git
    if not check_git_repo():
        print("ERREUR: Le répertoire actuel n'est pas un dépôt Git valide.")
        return
    
    # Affiche des informations de débogage
    if args.debug:
        print(f"Plateforme: {sys.platform}")
        print(f"Répertoire courant: {os.getcwd()}")
        print(f"Test de commande git:")
        test_cmd = "git --version"
        print(f"  {test_cmd} -> {run_command(test_cmd, shell=True)}")
    
    # Récupération de tous les commits (en ignorant les fusions)
    print("Récupération des commits...")
    commits = get_all_commits()
    
    if not commits:
        print("Aucun commit trouvé dans ce dépôt.")
        return
    
    print(f"Nombre de commits trouvés: {len(commits)}")
    
    # Génération du résumé global
    print("Génération du résumé global...")
    global_summary = generate_global_summary(commits)
    
    # Génération du journal d'activité quotidien
    print("Génération du journal d'activité quotidien...")
    daily_report = generate_daily_activity_report(commits)
    
    # Combinaison des deux rapports
    full_report = global_summary + "\n\n" + daily_report
    
    # Ajout du titre principal et de l'auteur
    header = "# Journal d'activité Git - Projet Universitaire\n"
    header += "## Auteur: P0ubelle\n"
    header += f"## Date de génération: {datetime.datetime.now().strftime('%Y-%m-%d')}\n\n"
    
    full_report = header + full_report
    
    # Affichage du rapport
    print("\nRésumé du rapport généré:")
    print("------------------------")
    print(f"Commits analysés: {len(commits)}")
    dates = set(commit['date'] for commit in commits)
    print(f"Jours d'activité: {len(dates)}")
    print(f"Période couverte: {min(dates) if dates else 'N/A'} à {max(dates) if dates else 'N/A'}")
    
    # Sauvegarde dans un fichier Markdown
    save_report_to_file(full_report, args.output)

if __name__ == "__main__":
    main()