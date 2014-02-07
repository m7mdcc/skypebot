execute 'enable_parter' do
  command "sed -i 's_# deb http://archive\.canonical\.com/ubuntu quantal partner_deb http://archive.canonical.com/ubuntu quantal partner_g' sources.list"
  cwd '/etc/apt/'
end

execute 'enable_parter_source' do
  command "sed -i 's_# deb-src http://archive\.canonical\.com/ubuntu quantal partner_deb-src http://archive.canonical.com/ubuntu quantal partner_g' sources.list"
  cwd '/etc/apt/'
  notifies :run, 'execute[apt-get update]', :immediately
end

