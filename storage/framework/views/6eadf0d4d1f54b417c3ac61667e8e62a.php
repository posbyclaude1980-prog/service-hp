<?php
    $currentPaper = $paper->value ?? 'a4';
    $baseQuery = request()->except(['paper', 'preview', 'print']);
    $buildUrl = function (array $extra = []) use ($baseQuery) {
        return request()->url().'?'.http_build_query(array_merge($baseQuery, $extra));
    };
?>
<div class="dm-toolbar">
    <strong>Dot Matrix Print</strong> — Epson LX-300 &nbsp;|&nbsp;
    <label for="paperSelect">Kertas:</label>
    <select id="paperSelect" onchange="location.href=this.value">
        <option value="<?php echo e($buildUrl(['paper' => 'a4', 'preview' => 1])); ?>" <?php if($currentPaper === 'a4'): echo 'selected'; endif; ?>>A4</option>
        <option value="<?php echo e($buildUrl(['paper' => 'f4', 'preview' => 1])); ?>" <?php if($currentPaper === 'f4'): echo 'selected'; endif; ?>>F4 (Folio)</option>
        <option value="<?php echo e($buildUrl(['paper' => 'continuous', 'preview' => 1])); ?>" <?php if($currentPaper === 'continuous'): echo 'selected'; endif; ?>>Continuous Form</option>
    </select>
    <button type="button" onclick="window.print()">Cetak</button>
    <a class="btn-dm" href="<?php echo e($buildUrl(['paper' => $currentPaper, 'print' => 1])); ?>" target="_blank">Buka &amp; Cetak Langsung</a>
    <a class="btn-dm" href="javascript:history.back()">Kembali</a>
</div>
<?php /**PATH C:\laragon\www\service-hp\resources\views/prints/partials/toolbar.blade.php ENDPATH**/ ?>