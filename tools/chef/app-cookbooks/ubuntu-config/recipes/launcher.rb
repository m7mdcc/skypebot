execute "launcher" do
	command "dbus-launch --exit-with-session gsettings set com.canonical.Unity.Launcher favorites \"['application://ubiquity-gtkui.desktop', 'application://nautilus-home.desktop', 'application://skype.desktop', 'application://gnome-terminal.desktop']\""
	user "vagrant"
end