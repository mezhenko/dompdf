# config valid only for Capistrano 3.1
lock '3.2.0'

set :application, 'dompdf'
set :repo_url, 'git@bitbucket.org:sendoo/personado-dompdf.git'
set :deploy_to, '/var/lib/personado-api/dompdf'
set :keep_releases, 3

namespace :deploy do
  desc 'Install composer dependencies'
  task :install do
    on roles(:app), in: :sequence, wait: 5 do
      within release_path do
        execute :composer, 'install'
      end
    end
  end
  after :publishing, :install
  after :publishing, :cleanup
end
