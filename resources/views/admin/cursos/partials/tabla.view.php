<?php if (!empty($subnivelesConCursos)): ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 50px;"></th>
                <th style="width: 80px;">ID</th>
                <th>Nombre del Subnivel / Curso</th>
                <th>Sección</th>
                <th style="width: 120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subnivelesConCursos as $subnivel): ?>
                <!-- Fila del Subnivel (Padre) -->
                <tr class="table-light font-weight-bold">
                    <td>
                        <?php if (!empty($subnivel['cursos'])): ?>
                            <!-- Apunta a la clase que compartirán sus cursos hijos -->
                            <button class="btn btn-sm btn-light btn-toggle-cursos" data-target="subnivel-<?= $subnivel['id'] ?>">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($subnivel['id']) ?></td>
                    <td colspan="2">
                        <span class="text-uppercase text-primary font-weight-bold">
                            <?= htmlspecialchars($subnivel['nombre']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info btn-editar-subnivel" data-id="<?= $subnivel['id'] ?>">
                            <i class="fas fa-pencil"></i>
                        </button>
                    </td>
                </tr>

                <!-- Filas de los Cursos (Hijos de este Subnivel) -->
                <?php if (!empty($subnivel['cursos'])): ?>
                    <?php foreach ($subnivel['cursos'] as $curso): ?>
                        <!-- La clase coincide exactamente con el data-target del botón padre -->
                        <tr class="subnivel-<?= $subnivel['id'] ?>" style="display: table-row; background-color: #ffffff;">
                            <td></td>
                            <td><?= htmlspecialchars($curso['id']) ?></td>
                            <td style="padding-left: 30px;">
                                <i class="fas fa-arrow-right text-muted mr-2"></i> 
                                <?= htmlspecialchars($curso['nombre']) ?>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars($curso['seccion']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success btn-editar-curso" data-id="<?= $curso['id'] ?>">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-curso" data-id="<?= $curso['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info text-center mt-3" role="alert">
        Aún no se han registrado Subniveles ni Cursos de Educación.
    </div>
<?php endif; ?>
