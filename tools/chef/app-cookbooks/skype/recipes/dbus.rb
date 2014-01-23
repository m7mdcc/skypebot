package "pkg-config" do
	action :install
end

%w{dbus libdbus-1-dev libxml2-dev}.each do |lib|
	package lib do
		action :install
	end
end

php_pear "DBus" do
	preferred_state "beta"
	action :install
end
