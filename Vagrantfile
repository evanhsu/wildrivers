# -*- mode: ruby -*-
# vi: set ft=ruby :


Vagrant.configure(2) do |config|
  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "scotch/box"

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
# config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
#  config.vm.network "public_network", ip: "192.168.1.250"

  # Create a private network.
  # You can use the IP listed here to connect to the virtual machine from your local host only.
  config.vm.network "private_network", ip: "192.168.33.10"


  config.vm.hostname = "siskiyourappellers"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  config.vm.synced_folder ".", "/var/www/public", :mount_options => ["dmode=777", "fmode=666"]

end
