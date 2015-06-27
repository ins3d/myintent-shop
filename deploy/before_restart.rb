cookbook_file "/etc/httpd/sites-available/myintent_shop.conf" do
  source "myintent_shop.conf"
  owner "root"
  group "apache"
  mode 00777
  action :create
end

link "/srv/www/myintent_shop/current/media" do
  to "/s3"
  owner "deploy"
  group "apache"
end

#need to add attributes
script "change_owner" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH

    {
        chmod -R 771 /srv/www/myintent_shop/current/media
	    chgrp -R apache /srv/www/myintent_shop/current/media
        chown -R deploy /srv/www/myintent_shop/current/media

  	} || {
  		echo "exception"
  	}
  EOH
end