# Native eBPLS Hardware Specifications #

The [User Guide](http://ebpls.googlecode.com/files/eBPLS%20User%20Guide.pdf) outlines configurations for the minimum and recommended hardware for the eBPLS server.

Recent hardware changes particularly SATA disk drives are not supported by the original Fedora Core 4 operating system.

VMWare Server has been installed (with eBPLS running as a virtual machine) on different Linux distributions and Windows operating systems to get around this problem and to create testing and fallback environments.


# Intel Core 2 Duo #

## Asus P5K Mobo ##
from Dave Asuncion (Nov 5, 2008)

  * Intel Pentium Core 2 Duo,
  * 2 SATA drives with Marvell SATA controller,
  * one DVD drive,
  * 2 GB of RAM.

Note:
  1. I had to use all-generic-ide at the boot prompt to make it work.

## MSI P31 ##
from "Frederick" <jrkrugger@...> (Nov 3, 2008)

  * Motherboard: MSI P31 Neo
  * Processor: Intel Dual Core
  * Video Card: Nvidia Inno3d PCIE
  * RAM: 1GB DDR2
  * Hard Drive: 80GB SATA
  * DVD\Writer: Sony

Notes:
  1. Disable the on-board LAN in setup and install a PCI Ethernet Card (Realtek Model RTL8139).  Due to unsupported chipset of built-in LAN (Model 8168/8111).