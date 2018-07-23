
plugin.tx_brapersonukd_pi1 {
  view {
    templateRootPaths.0 = EXT:bra_person_ukd/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_brapersonukd_pi1.view.templateRootPath}
    partialRootPaths.0 = EXT:bra_person_ukd/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_brapersonukd_pi1.view.partialRootPath}
    layoutRootPaths.0 = EXT:bra_person_ukd/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_brapersonukd_pi1.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_brapersonukd_pi1.persistence.storagePid}
    #recursive = 1
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }

}
plugin.tx_brapersonukd{
  settings{
  	import{
  		limit = 2
  		storagePid = 1
  		#docRoot = /var/www/vhosts/typo3-7/httpdocs/fileadmin/
  	}
  }
}  

