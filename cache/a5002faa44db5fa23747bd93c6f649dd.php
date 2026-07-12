            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="ion-close"></i>
                </button>

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="index.html" class="logo"><i class="mdi mdi-assistant"></i> Annex</a>
                        <!-- <a href="index.html" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> -->
                    </div>
                </div>

                <div class="sidebar-inner slimscrollleft">

                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">Administrador</li>

                            <?php if (!empty($_SESSION['menu_dinamico']) && is_array($_SESSION['menu_dinamico'])): ?>
                            <?php foreach ($_SESSION['menu_dinamico'] as $menu): ?>

                            <?php if (empty($menu['submenus'])): ?>
                            <!-- 1. ENLACE SIMPLE (Sin submenús. Ej: Dashboard) -->
                            <li>
                                <a href="<?= htmlspecialchars(RUTA_URL . "/" . $menu['url']) ?>" class="waves-effect">
                                    <i class="<?= htmlspecialchars($menu['icono'] ?? 'mdi mdi-circle-outline') ?>"></i>
                                    <span> <?= htmlspecialchars($menu['nombre']) ?> </span>
                                </a>
                            </li>
                            <?php else: ?>
                            <!-- 2. MENÚ DESPLEGABLE (Con submenús. Ej: Administración) -->
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect">
                                    <i class="<?= htmlspecialchars($menu['icono'] ?? 'mdi mdi-folder') ?>"></i>
                                    <span> <?= htmlspecialchars($menu['nombre']) ?> </span>
                                    <span class="float-right"><i class="mdi mdi-chevron-right"></i></span>
                                </a>
                                <ul class="list-unstyled">
                                    <?php foreach ($menu['submenus'] as $submenu): ?>
                                    <li>
                                        <a href="<?= htmlspecialchars(RUTA_URL . "/" . $submenu['url']) ?>">
                                            <?= htmlspecialchars($submenu['nombre']) ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php endif; ?>

                            <?php endforeach; ?>
                            <?php else: ?>
                            <!-- Mensaje de respaldo si el usuario no tiene menús asignados -->
                            <li>
                                <a href="javascript:void(0);" class="waves-effect">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                    <span> Sin accesos asignados </span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
            </div>
            <!-- Left Sidebar End -->
