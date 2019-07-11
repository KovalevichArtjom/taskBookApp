<?php /*//admin login and password*/?>
<div id="modal-add-admin" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="add-admin" class="modal__form" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title page__title">Окно входа администратора</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="loginAdmin">Логин</label>
                        <input type="text" class="col form-control" id="loginAdmin" placeholder="Логин администратора">
                        <label for="passwordAdmin">Пароль</label>
                        <input type="password" id="passwordAdmin" class="col form-control" placeholder="Пароль администратора">
                        <div class="form-error passwordAdmin"></div>
                    <div class="modal-footer admin_footer">
                        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Войти"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /*//admin login and password*/?>
<?php /*//modal editing task*/
        if (!empty($modalEditingTask)){?>
        <div id="modal-editing-task-admin" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="editing-task-admin" class="modal__form" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title page__title">Окно редактирования</div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php /*//first name used */?>
                            <div class="col-md admin_name">
                                <label>Пользователь</label>
                                <input type="text" class="form-control" id="nameAdmin" disabled>
                            </div>
                            <?php /*//email used*/?>
                            <div class="col-md admin_email">
                                <label for="validationCustomUsername">E-mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    </div>
                                    <input type="text" class="form-control" id="emailAdmin" aria-describedby="inputGroupPrepend" disabled>
                                </div>
                            </div>
                            <div class="col-md textTasksAdmin">
                                <label>Задача</label>
                                <textarea class="form-control" id="textTasksAdmin" rows="3" ></textarea>
                                <div class="form-error"></div>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">отредактировано администратором</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="редактировать"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row_admin">
            <div class="col tasks_button__checkingUser">
                <button type="button" class="btn btn-primary btn-lg btn-block">Войти под правами пользователя</button>
            </div>
        </div>
<?php } /*//End modal editing task*/?>
<?php if(empty($modalEditingTask)){?>
        <div class="row_admin">
            <div class="col tasks_button__checkingAdmin">
                <button type="button" class="btn btn-primary btn-lg btn-block">Войти под правами администратора</button>
            </div>
        </div>
<?php }?>
<div class="container-fluid title">
    <h1>Приложение-задачник<?php echo $AddInTitleStatus?></h1>
</div>
<div class="tasks">
    <select class="custom-select tasks_sorting" >
        <option <?php if($sortingTask == 0) echo "selected " ?>value="0">Сортировать по Имени пользователя</option>
        <option <?php if($sortingTask == 1) echo "selected " ?>value="1">Сортировать по Электронной почте</option>
        <option <?php if($sortingTask == 2) echo "selected " ?>value="2">Сортировать по Статусу задачи</option>
    </select>
    <div class="row">
        <div class="col-1 tasks_header idUser">id</div>
        <div class="col-2 tasks_header nameUser">Пользователь</div>
        <div class="col-2 tasks_header emailUser">е-mail</div>
        <div class="col-5 tasks_header taskUser">Задача</div>
        <div class="col-2 tasks_header statusTask">Статус</div>
    </div>
    <div class="row">
        <?php  if(is_array($ListTasks) and count($ListTasks) > 0) {
            foreach ($ListTasks as $attrs){ ?>
                <div class="col-1 tasks_body idUser"><?php echo $attrs['id']?></div>
                <div class="col-2 tasks_body nameUser" id="nameUser_<?php echo $attrs['id']?>">
                    <?php echo $attrs['name']?>
                </div>
                <div class="col-2 tasks_body emailUser" id="emailUser_<?php echo $attrs['id']?>">
                    <?php echo $attrs['email']?>
                </div>
                <div class="col-5 tasks_body taskUser" id="taskUser_<?php echo $attrs['id']?>">
                    <?php echo $attrs['textTasks']?>
                </div>
                <div class="col-2 tasks_body statusTask" id="statusTask_<?php echo $attrs['id']?>"
                     data-statusTask="<?php echo $attrs['statusTasks']?>">
                    <span style="color: <?php if($attrs['statusTasks'] == 0) echo 'red';
                                              if($attrs['statusTasks'] == 1) echo 'green' ?>;">
                    <?php if($attrs['statusTasks'] == 0) echo 'в ожидании действий администратора';
                     if($attrs['statusTasks'] == 1) echo 'отредактировано администратором'?>
                    </span>
                </div>

            <?php }
        }else {?>
            <div class="col-1 tasks_body idUser">Null</div>
            <div class="col-2 tasks_body nameUser">Null</div>
            <div class="col-2 tasks_body emailUser">Null</div>
            <div class="col-5 tasks_body taskUser">Null</div>
            <div class="col-2 tasks_body taskUser">Null</div>
        <?php };?>
    </div>
    <div class="row">
        <div class="col tasks_button__add">
            <button type="button" class="btn btn-primary btn-lg btn-block">Добавить задачу</button>
        </div>
    </div>
    <?php /*//pagination*/?>
    <?php if($amountPage > 1){
        $numberPage = 1;
        ?>
    <nav aria-label="">
        <ul class="pagination justify-content-center">
            <?php if($pagination == $amountPage){?>
            <li class="page-item">
                <a class="page-link" href="?sortingTask=<? echo $sortingTask?>&pagination=<? echo $backPage ?>" tabindex="-1">предыдущая</a>
            </li>
            <?php }
            while($numberPage <= $amountPage){?>
            <li class="page-item"><a class="page-link" href="?sortingTask=<? echo $sortingTask?>&pagination=<? echo $numberPage ?>"><? echo $numberPage ?></a></li>
            <?php $numberPage++;}
                if($pagination < $amountPage){?>
            <li class="page-item">
                <a class="page-link" href="?sortingTask=<? echo $sortingTask?>&pagination=<? echo $nextPage ?>">следующая</a>
            </li>
            <?php }?>
        </ul>
    </nav>
    <?php  } /*//End pagination*/?>
</div>
<?php /*//modal add task */?>
<div id="modal-add-task" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="add-task" class="modal__form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title page__title">Окно добавления задач</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php /*//first name used */?>
                    <div class="col-md name">
                        <label>Пользователь</label>
                        <input type="text" class="form-control" id="name" >
                        <div class="form-error"></div>
                    </div>
                    <?php /*//email used*/?>
                    <div class="col-md email">
                        <label for="validationCustomUsername">E-mail</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                            </div>
                            <input type="text" class="form-control" id="email" aria-describedby="inputGroupPrepend" >
                            <div class="form-error"></div>
                        </div>
                    </div>
                    <div class="col-md textTasks">
                        <label>Задача</label>
                        <textarea class="form-control" id="textTasks" rows="3" ></textarea>
                        <div class="form-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="добавить"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php /*//end modal add task */?>
