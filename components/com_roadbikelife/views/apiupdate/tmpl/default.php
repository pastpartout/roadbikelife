<div id="stravaupdate">
    <div class="container py-3">
        <h1>
            Strava Update erfolgreich: <?php echo count($this->items) ?> Aktivitäten aktualisiert
        </h1>
        <div class="p-3 border rounded mb-4">
            <p><strong>Dauer: </strong>
				<?php echo sprintf('%02d:%02d',
					floor($this->execution_time / 60 % 60),
					floor($this->execution_time % 60));
				?>
            </p>
        </div>
		<?php if (count($this->items) > 0): ?>
            <div id="accordion" class="mb-5">
				<?php foreach ($this->items as $item): ?>
                    <div class="card">
                        <!-- panel class must be in -->
                        <a href="#acti_<?php echo $item->id ?>" data-parent="#accordion" data-toggle="collapse"
                           class="card-header p-2">
                            <h5 class="d-flex align-items-center justify-content-between mb-0">
                                <span class="mr-2"> <?php echo $item->name ?></span>
                                <span class="badge badge-dark badge small"><?php echo $item->id ?></span>
                            </h5>
                        </a>
                        <div class="card-body collapse " id="acti_<?php echo $item->id ?>">
                            <?php foreach ($item->extra_fields as $type => $extra_field): ?>
                                <h5><?php echo $type ?> JSON</h5>
                                <pre class="json-viewer border rounded"><?php echo $extra_field ?></pre>
                            <?php endforeach; ?>

                            <?php foreach ($item->streams as $type => $stream): ?>
                                <h5><?php echo $type ?> JSON</h5>
                                <pre class="json-viewer border rounded">
                                    <?php echo $stream ?>
                                </pre
                            <?php endforeach; ?>
                        </div>
                    </div>

				<?php endforeach; ?>
            </div>
		<?php endif ?>
    </div>
</div>