
plugin.tx_brapersonukd_pi1 {
  view {
    # cat=plugin.tx_brapersonukd_pi1/file; type=string; label=Path to template root (FE)
    templateRootPath = EXT:bra_person_ukd/Resources/Private/Templates/
    # cat=plugin.tx_brapersonukd_pi1/file; type=string; label=Path to template partials (FE)
    partialRootPath = EXT:bra_person_ukd/Resources/Private/Partials/
    # cat=plugin.tx_brapersonukd_pi1/file; type=string; label=Path to template layouts (FE)
    layoutRootPath = EXT:bra_person_ukd/Resources/Private/Layouts/
  }
  persistence {
    # cat=plugin.tx_brapersonukd_pi1//a; type=string; label=Default storage PID
    storagePid = 1
  }
}
