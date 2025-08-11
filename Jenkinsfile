pipeline {
    agent any

    environment {
        SSH_CREDENTIALS_ID = 'jenkins-ssh-private-key' // Replace with your Jenkins credentials ID
        LARAVEL_PROJECT_PATH = '/var/www/html/path-of-ur-project' // Updated Laravel project path
        VM_HOST = '172.16.16.145' // Updated host
        SSH_USER = 'root' // Login using root
    }

    stages {
        stage('Deploy Laravel Project') {
            steps {
                deployToVM()
            }
        }
    }

    post {
        success {
            echo 'Latest changes deployed successfully!'
        }
        failure {
            echo 'Oops. Deployment failed.'
        }
    }
}

def deployToVM() {
    script {
        sshagent(credentials: [env.SSH_CREDENTIALS_ID]) {
            try {
                sh """
                ssh -o StrictHostKeyChecking=no ${env.SSH_USER}@${env.VM_HOST} '
                cd ${env.LARAVEL_PROJECT_PATH} &&
                echo "Pulling latest changes on ${env.VM_HOST}..." &&
                git fetch --all &&
                git reset --hard origin/main &&
                git pull origin main &&
                echo "Setting permissions and caching configuration on ${env.VM_HOST}..." &&
                composer update &&
                composer dump-autoload &&
                php artisan tenants:migrate &&
                php artisan migrate &&
                php artisan optimize:clear &&
                docker compose up app web mail queue cron filebeat -d
                '
                """
                echo "Deployment to ${env.VM_HOST} succeeded."
            } catch (Exception e) {
                echo "Deployment to ${env.VM_HOST} failed: ${e.message}"
                currentBuild.result = 'FAILURE'
            }
        }
    }
}
