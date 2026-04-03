<?php

$time_start = "08:00";
$time_end   = "18:00";

$data       = "2026-04-02";
$data_end   = "2026-04-30";

?>



<section>
    <a href="index.php">
        <button class="btn btn-danger mt-3">Voltar</button>
    </a>

    <h2 class="mt-3">Agendamento:</h2>

    <form method="post">
        <div class="form-group">
            <label class="form-label">Data</label>
            <input type="date" name="date" class="form-control" min="<?php echo $data ?>" max="<?php echo $data_end ?>" required>

            <label class="form-label">Horário do Atendimento</label>
            <input type="time" name="time_start" class="form-control" min="<?php echo $time_start ?>" max="<?php echo $time_end ?>" required>
        </div>
    </form>
</section>