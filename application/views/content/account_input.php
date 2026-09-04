<?php 
if(empty($edit)){
    $username = "";
    $name = "";
    $level = "";
}else{
    $username = $edit->username;
    $name = $edit->name;
    $level = $edit->level;
}
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Username</div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="username" value="<?= $username; ?>" placeholder="Username" required>
                        </div>
                    </div>
                    <?php 
                    if(empty($edit)){
                        ?>
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Password</div>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Nama</div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="name" placeholder="Nama" value="<?= $name; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Level</div>
                        <div class="col-lg-8">
                            <select class="form-control" name="level" required>
                                <option value="" <?php if($level == ""){ echo "selected"; } ?> disabled selected>Level</option>
                                <option value="Super Admin" <?php if($level == "Super Admin"){ echo "selected"; } ?>>Super Admin</option>
                                <option value="Admin" <?php if($level == "Admin"){ echo "selected"; } ?>>Admin</option>
                                <option value="Tracer" <?php if($level == "Tracer"){ echo "selected"; } ?>>Tracer</option>
                                <option value="Komandan" <?php if($level == "Komandan"){ echo "selected"; } ?>>Komandan</option>
                                <option value="Cashier" <?php if($level == "Cashier"){ echo "selected"; } ?>>Cashier</option>
                            </select>
                        </div>
                    </div>
                    <div align="right">
                        <a href="<?= base_url("account"); ?>" class="btn btn-danger" name="btn-simpan">Kembali</a>
                        <button class="btn btn-info" name="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>