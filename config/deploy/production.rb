set :stage, :production
server 'production-personado.arkency', user: 'personado-api', roles: %w{web app db}
