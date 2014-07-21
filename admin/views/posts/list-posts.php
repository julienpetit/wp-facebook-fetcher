<?php foreach ($posts as $key => $post) : ?>
        <?php if (!isset($post->message)) continue; ?>
      <div class="post-item">
          <p>
            <img class="post-img" src="<?php echo $post->picture; ?>" alt="...">
            <?php echo $post->message; ?>
          </p>
          <span class="date"><?php echo (new Datetime($post->created_time))->format('Y-m-d H:i:s'); ?></span>


          <button class="button button-primary button-small continue-with-this-post" data-postid="<?php echo $post->id; ?>" data-postbody="<?php echo $post->message; ?>" data-postcreatedat="<?php echo (new Datetime($post->created_time))->format('Y-m-d H:i:s'); ?>">Continuer avec cet article</button>

        <div class="clearfix"></div>
      </div>
<?php endforeach; ?>



