<ul class="left hide-on-med-and-down">
    <?php foreach ($data as $item) { ?>
    <li><a href="<?php echo $item['slug']; ?>"><?php echo $item['title']; ?></a></li>
    <?php } ?>
</ul>