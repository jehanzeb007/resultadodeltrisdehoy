<div class="row content-block" id="draw-setion">
    <section class="col-content">
        <div id="subscribe_wrapper" class="box comments-area">
            <div class="comment-respond">
                <p id="reply-title" class="comment-reply-title box-title">
                    Dejar una respuesta
                </p>
                <form action="" method="post" id="commentform" class="comment-form">
                    <?php if(isset($errors) && !empty($errors)){?>
                        <div class="info-msg">
                            <i class="fa fa-info-circle"></i>
                            <?php foreach($errors as $error){echo $error.'<br>';} ?>
                        </div>
                    <?php }?>
                    <?php if(isset($_SESSION['comment-post']) && $_SESSION['comment-post'] == 'true'){unset($_SESSION["comment-post"]); ?>
                        <div class="success-msg">
                            <i class="fa fa-check"></i>
                            Comentar con éxito.
                        </div>
                    <?php } ?>
                    <input type="hidden" name="type" value="post-comment">
                    <input type="hidden" name="page" value="<?=cleanSlash($_SERVER['REQUEST_URI'])?>">
                    <p class="comment-notes">
                        <small>Su dirección de correo electrónico no se publicará.</span> <small class="required-field-message">Los campos obligatorios están marcados <span class="required">*</span></small>
                    </p>
                    <p class="comment-form-comment">
                        <label for="comment">Comentario <span class="required">*</span></label>
                        <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required=""><?=isset($_POST['comment'])?$_POST['comment']:''?></textarea>
                    </p>
                    <div class="comment-form-author">
                        <label for="author">Nombre <span class="required">*</span></label>
                        <input id="author" name="name" type="text" value="<?=isset($_POST['name'])?$_POST['name']:''?>" size="30" maxlength="245" autocomplete="name" required="" />
                    </div>
                    <div class="comment-form-email">
                        <label for="email">Correo electrónico <span class="required">*</span></label>
                        <input id="email" name="email" type="email" value="<?=isset($_POST['email'])?$_POST['email']:''?>" size="30" maxlength="100" aria-describedby="email-notes" autocomplete="email" required="" />
                    </div>
                    <div class="form-submit">
                        <input name="submit" type="submit" id="submit" class="btn-post-comment" value="Publicar Comentario" />
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<div class="row"><div class="date-chooser flex-grow-1"><p><button style="color: #000;padding: 15px;background-color: #fff;font-size: 15px;font-weight: bold;cursor: pointer" href="javascript:void(0)" onclick="loadComments(this)"><i class="fa fa-comment"></i>&nbsp; Haga clic para Cargar Comentarios </button></p></div></div><div class="comment_block" id="loaded_comments" style="background-color: #fff;padding: 10px;display: none;margin-bottom: 10px;"></div>