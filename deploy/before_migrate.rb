script "deploy_new" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
    fusermount -u /srv/www/prod_myintent_shop/current/media
  EOH
end