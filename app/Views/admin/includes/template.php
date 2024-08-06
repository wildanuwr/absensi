<?= $this->include('includes/head') ?>
<?= $this->include('includes/sidebar') ?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- tempat navar -->
        <?= $this->include('includes/header') ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           <?= $this->renderSection('konten') ?>


        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
    <!-- tempat Footer -->
    <?= $this->include('includes/footer') ?>