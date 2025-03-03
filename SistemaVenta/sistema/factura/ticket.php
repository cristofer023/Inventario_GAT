<?php
    $subtotal   = 0;
    $iva        = 0;
    $impuesto   = 0;
    $tl_sniva   = 0;
    $total      = 0; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta</title>
    <link rel="stylesheet" href="styleticket.css">
</head>
<body>

<?php echo $anulada; ?>

<div id="page_pdf">
    <table id="factura_head">
        <tr>
            <td class="logo_factura">
                <img src="img/<?php echo $configuracion['foto']; ?>">
            </td>
            <td class="info_empresa">
                <?php if ($result_config > 0) {
                    $iva = $configuracion['iva'];
                    $moned = $configuracion['moneda'];
				
                ?>
                <div>
                    <h2><?php echo strtoupper($configuracion['nombre']); ?></h2>
                    <p><?php echo $configuracion['razon_social']; ?></p>
                    <p><?php echo $configuracion['direccion']; ?></p>
                    <p>RUC: <?php echo $configuracion['nit']; ?> &nbsp;&nbsp; Cel: <?php echo $configuracion['telefono']; ?></p>
                </div>
			
                <?php
				}
                    if ($venta['status'] == 1) {
                        $tipo_pago = 'Contado';
                    } elseif ($venta['status'] == 3) {
                        $tipo_pago = 'Crédito';
                    } else {
                        $tipo_pago = 'Anulado';
                    }

                    if ($tipo_pago == 'Crédito') {
                        date_default_timezone_set("America/Managua");
                        $fecha = date('d-m-Y', strtotime($venta["fecha"]));
                        $fecha_a_vencer = date('d-m-Y', strtotime($fecha . ' + 30 days'));
                        $vence = "<p>Vencimiento: $fecha_a_vencer</p>";
                    } else {
                        $vence = '';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td class="info_venta">
                <div class="round">
                    <p><strong>Tipo de venta:</strong> <?php echo $tipo_pago; ?></p>
                    <p><strong>No. Venta:</strong> <?php echo str_pad($venta['noventa'], 11, '0', STR_PAD_LEFT); ?></p>
                    <p><strong>Fecha:</strong> <?php echo $venta['fechaF']; ?></p>
                    <p><strong>Hora:</strong> <?php echo $venta['horaF']; ?></p>
                    <p><strong>Vendedor:</strong> <?php echo $venta['vendedor']; ?></p>
                    <p><strong>Cliente:</strong> <?php echo $venta['nombre']; ?></p>
                    <p><strong>NIT:</strong> <?php echo $venta['nit']; ?></p>
                    <?php echo $vence; ?>
                </div>
            </td>
        </tr>
    </table>

    <table id="factura_detalle">
        <thead>
            <tr>
                <th colspan="4" class="textleft"><hr> Descripción</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
            <tr>
                <th colspan="4"><hr></th>
            </tr>
        </thead>
        <tbody id="detalle_productos">
        <?php
            if ($result_detalle > 0) {
                while ($row = mysqli_fetch_assoc($query_productos)) {
                    $precio_venta = number_format($row['precio_venta'], 2);
                    $precio_total = number_format($row['precio_total'], 2);
        ?>
            <tr>
                <td><?php echo $row['codigo']; ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td><?php echo $precio_venta; ?></td>
                <td><?php echo $precio_total; ?></td>
            </tr>
        <?php
                    $subtotal += $row['precio_total'];
                }
            }

            $impuesto  = round($subtotal * ($iva / 100), 2);
            $tl_sniva  = round($subtotal - $impuesto, 2);
            $total     = $tl_sniva + $impuesto;
            $descuento = number_format($venta['descuento'], 2);
        ?>
        </tbody>
        <tfoot id="detalle_totales">
            <tr>
                <td colspan="4"><hr></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><strong>Subtotal:</strong></td>
                <td><?php echo $moned . ' ' . number_format($tl_sniva, 2); ?></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><strong>Descuento:</strong></td>
                <td><?php echo $moned . ' ' . $descuento; ?></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><strong>Total:</strong></td>
                <td><?php echo $moned . ' ' . number_format($total - $descuento, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="mensaje_final">
        <h4 class="label_gracias">¡Gracias por su compra!</h4>
        <p class="label_gracias">Revise su producto, no aceptamos devoluciones.</p>
    </div>
</div>

</body>
</html>
