
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
  	}
  }
}  

plugin.tx_brapersonukd._CSS_DEFAULT_STYLE (
    textarea.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    input.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    .tx-bra-person-ukd table {
        border-collapse:separate;
        border-spacing:10px;
    }

    .tx-bra-person-ukd table th {
        font-weight:bold;
    }

    .tx-bra-person-ukd table td {
        vertical-align:top;
    }

    .typo3-messages .message-error {
        color:red;
    }

    .typo3-messages .message-ok {
        color:green;
    }
)
