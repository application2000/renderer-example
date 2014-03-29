<?php /* @type  \League\Plates\Template  $this */ ?>
<?php $this->layout('partials::head') ?>

<p>Hello, I'm <?php echo $this->data['name']; ?> and I am a <?php echo $this->data['title']; ?>.</p>
