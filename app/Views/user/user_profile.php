 <!-- App Header -->
 <div class="appHeader bg-primary text-light">
     <div class="left">
         <a href="javascript:;" class="headerButton goBack">
             <i class="fas fa-arrow-left fa-2x"></i>
         </a>
     </div>
     <div class="pageTitle"><?= $judul ?></div>
     <div class="right"></div>
 </div>
 <!-- * App Header -->

 <div class="section full m-4 mt-2">
     <div class="section-title"><?= $judul ?></div>
     <!-- App Capsule -->
     <div id="appCapsule" class="pt-3">
         <form action="<?= base_url('user/updateUser/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
             <?= csrf_field() ?>
             <div class="form-group d-flex justify-content-center align-items-center">
                 <?php if ($user['foto'] && file_exists(FCPATH . 'img/profile/' . $user['foto'])): ?>
                     <img class="top-0 start-50 translate-middle" src="<?= base_url('img/profile/' . esc($user['foto'])); ?>" alt="Foto Profil" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                 <?php else: ?>
                     <p>Belum ada foto</p>
                 <?php endif; ?>
             </div>

             <!-- Input untuk mengganti foto profil -->
             <div class="form-group boxed">
                 <label for="foto">Unggah Foto Profil Baru</label>
                 <input type="file" class="form-control-file" id="foto" name="foto">
             </div>
             <!-- Formulir data pengguna -->
             <div class="form-group boxed">
                 <label for="Nama">Nama</label>
                 <input type="text" class="form-control" id="Nama" name="Nama" value="<?= esc($user['Nama']) ?>" required>
             </div>
             <div class="form-group boxed">
                 <label for="email">Email address</label>
                 <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required>
             </div>
             <div class="form-group boxed">
                 <label for="jabatan">Jabatan</label>
                 <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= esc($user['jabatan']) ?>" required>
             </div>
             <div class="form-group boxed">
                 <label for="password m-2">Password</label>
                 <input type="password" class="form-control" id="password" name="password">
             </div>
             <div class="form-group boxed">
                 <label for="no_hp">No Hp</label>
                 <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= esc($user['no_hp']) ?>" required>
             </div>
             <div class="modal-footer">
                 <button type="submit" class="btn btn-primary">Simpan</button>
             </div>
             <!-- * App Capsule -->
         </form>
     </div>
     <br>
     <br>
     <br>
     <br>
 </div>