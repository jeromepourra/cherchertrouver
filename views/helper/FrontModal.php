<?php

class FrontModal extends FrontData {

    public static function printModal() {

        $aModals = Session::___tmp___getModal();

        if (isset($aModals)) {

            foreach ($aModals as $i => $aModal) {
                echo "
                    <div class='modal fade' id='modal" . $i . "' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered'>
                            <div class='modal-content'>
                                <div class='modal-header text-bg-primary'>
                                    <h6 class='modal-title fs-4'>
                                        " . $aModal["title"] . "
                                    </h6>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                </div>
                                <div class='modal-body'>
                                    " . $aModal["body"] . "
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-sm btn-primary' data-bs-dismiss='modal'>Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }

        }

    }

    public static function showModal() {

        $aModals = Session::___tmp___getModal();

        if (isset($aModals)) {

            foreach ($aModals as $i => $aModal) {
                echo "
                    <script>
                        new bootstrap.Modal(document.getElementById('modal" . $i . "')).show();
                    </script>
                ";
            }

            unset($_SESSION["___tmp___"]["modals"]);

        }

    }

}