<?php /* @type  \League\Plates\Template  $this */ ?>
<?php $this->layout('partials::head') ?>
<?php $display = $this->model->getData(); ?>

<p>Hello, I'm <?php echo $display['name']; ?> and I am a <?php echo $display['title']; ?>.</p>
