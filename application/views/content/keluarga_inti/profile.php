<?php 
$id = $this->user_id;
$get_user_password = $this->admin_model->get_data_select("penitip","username,password","id = '".$this->user_id."'","row");
$username = $get_user_password->username;
$password = $get_user_password->password;
$level = $this->level;

if(empty($username) && empty($password)){
    ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <form action="<?= base_url("simpan_profile_ki") ?>" method="post">
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Username</div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="username" placeholder="Masukkan Username" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Password</div>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" name="password" placeholder="Masukkan Password" required>
                            </div>
                        </div>
                        <div align="right">
                            <button class="btn btn-info" name="btn-simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}else{
    ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <form action="<?= base_url("simpan_username_ki") ?>" method="post">
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Username</div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="username" value="<?= $username; ?>" placeholder="Username" required>
                            </div>
                        </div>
                        <div align="right">
                            <a href="javascript:void(0)" data-toggle="modal" title="Rubah Password" data-target="#exampleModal" onclick="np(<?= $this->user_id ?>)" class="btn btn-success">Rubah Password</a>
                            
                            <button class="btn btn-info" name="btn-simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rubah Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("simpan_password_ki") ?>" method="post">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password Baru">
                    <div class="mt-2" align="right">
                        <a href="javascript:void(0)" class="btn btn-secondary" data-dismiss="modal">Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>