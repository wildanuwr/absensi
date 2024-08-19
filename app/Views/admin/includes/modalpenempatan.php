<div class="modal fade" id="ModalPenempatan" tabindex="-1" aria-labelledby="ModalPenempatan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalPenempatanLabel">Penempatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk update penempatan jika $user tersedia -->
                <?php if ($user !== null): ?>
                    <form action="<?= base_url('admin/updatePenempatan') ?>" method="post">
                        <div class="form-group">
                            <label for="nama_lokasi">Pilih Nama :</label>
                            <select class="custom-select" name="Nama" id="Nama">
                                <?php foreach ($usernama as $nama): ?>
                                    <option value="<?= $nama['Nama'] ?>" <?= $nama['Nama'] == $nama['Nama'] ? 'selected' : '' ?>>
                                        <?= $nama['Nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nama_lokasi">Pilih Lokasi Penempatan:</label>
                            <select class="custom-select" name="nama_lokasi" id="nama_lokasi">
                                <?php foreach ($lokasi as $tempat): ?>
                                    <option value="<?= $tempat['nama_lokasi'] ?>" <?= $tempat['nama_lokasi'] == $tempat['nama_lokasi'] ? 'selected' : '' ?>>
                                        <?= $tempat['nama_lokasi'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p>Data user tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>