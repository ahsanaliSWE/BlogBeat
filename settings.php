<?php
    session_start();

    require_once("require/database.php");
    require_once("require/general.php");


    if(!isset($_SESSION['user'])){
        header("Location: login.php?msg=You must be logged in to access this page.");
        exit();
    }

    if(isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 1){
        header("Location: admin/admin_dashboard.php?msg=You do not have permission to access this page.");
        exit();
    }

    General::site_header("Settings");

    General::site_navbar(true, $_SESSION['user']['first_name'], $_SESSION['user']['last_name'], $_SESSION['user']['user_image'], "Settings");


    ?>
       <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4">
                        <h4 class="mb-0"><i class="fa-solid fa-sliders"></i> Website Theme Settings</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($msg)){ ?>
                            <div class="alert alert-success"><?= $msg ?></div>
                        <?php } ?>
                        <form method="POST" action="process/process_settings.php">
                            <div class="mb-3">
                                <label for="font_style" class="form-label">Font Style</label>
                                <select name="font_style" id="font_style" class="form-select">
                                    <?php
                                    $fonts = ['Arial', 'Verdana', 'Georgia', 'Tahoma', 'Times New Roman'];
                                    $selected_font = $_SESSION['themes']['font_style'] ?? 'Arial';
                                    foreach ($fonts as $font) {
                                        $selected = ($selected_font === $font) ? 'selected' : '';
                                        echo "<option value='$font' $selected>$font</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="background_color" class="form-label">Background Color</label>
                                <input type="color" name="background_color" id="background_color" class="form-control form-control-color"
                                       value="<?= htmlspecialchars($_SESSION['themes']['background_color'] ?? '#ffc107') ?>">
                            </div>
                            
                            <button type="submit" class="btn btn-danger w-100 mb-2" name="reset_settings">
                                <i class="fa-solid fa-rotate-left"></i> Reset to Default
                            </button>

                            <button type="submit" class="btn btn-success w-100" name="save_settings">
                                <i class="fa-solid fa-floppy-disk"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php

    General::site_footer();
    General::site_script();
?>