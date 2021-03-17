  
<div class="alert alert-{{ $type }}" data-alert="alert">
    <button class="close" type="button" data-dismiss="alert">×</button>
    <small>{!! $message !!}</small>

    <?php
    foreach ($errors as $err) {
        ?>
        <small>{!! $err !!}</small> 
    <?php }
    ?>
</div>


