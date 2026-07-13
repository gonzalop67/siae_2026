<?php if (!empty($subniveles)): ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 50px;"></th>
                <th>ID</th>
                <th>Nombre del Nivel / Subnivel</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subniveles as $nivel): ?>
                <tr class="nivel-padre" data-id="<?= $nivel['id'] ?>">
                    <td>
                        <?php if (!empty($nivel['subniveles'])): ?>
                            <button class="btn btn-sm btn-light btn-toggle-subnivel" data-target="<?= $nivel['id'] ?>">
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($nivel['id']) ?></td>
                    <td><strong><?= htmlspecialchars($nivel['nombre']) ?></strong></td>
                    <td>
                        <button class="btn btn-sm btn-info btn-editar-nivel" data-id="<?= $nivel['id'] ?>">
                            <i class="fas fa-pencil"></i>
                        </button>
                    </td>
                </tr>
                <?php if (!empty($nivel['subniveles'])): ?>
                    <?php foreach ($nivel['subniveles'] as $subnivel): ?>
                        <tr class="subnivel-fila subnivel-of-<?= $nivel['id'] ?>" style="display: table-row; background-color: #f8f9fa;">
                            <td></td>
                            <td><?= htmlspecialchars($subnivel['id']) ?></td>
                            <td style="padding-left: 30px;">
                                <i class="mdi mdi-arrow-bottom-right text-muted"></i>
                                <?= htmlspecialchars($subnivel['nombre']) ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success btn-editar-subnivel" data-id="<?= $subnivel['id'] ?>">
                                    <i class="fas fa-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-subnivel" data-id="<?= $subnivel['id'] ?>">
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
        Aún no se han registrado Niveles ni Subniveles de Educación.
    </div>
<?php endif; ?>
