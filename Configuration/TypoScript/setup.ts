
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
    recursive = 99
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
  		    limit = 10
            storagePid = 1
            lumpensammlerStoragePid = 6988
            lumpensammlerFilePath = lumpensammler/
            logfileName = personImportlog.txt
  	    }
  	    convert{
  	        limit = 9999999
            storagePid = 1
            logfileName = personConvertlog.txt
            adminUid = 1
        }
    }
}  

