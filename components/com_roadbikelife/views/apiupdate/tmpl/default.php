<div id="stravaupdate">
    <div class="container py-3">
        <h1>
            Strava Update erfolgreich: <?= count($this->items) ?> Aktivitäten aktualisiert
        </h1>
        <div class="p-3 border rounded mb-4">
            <p><strong>Dauer: </strong>
				<?= sprintf('%02d:%02d',
					floor($this->execution_time / 60 % 60),
					floor($this->execution_time % 60));
				?>
            </p>
			<? if (count($this->items) > 0): ?>
                <p><strong>Betroffene Beiträge</strong></p>
                <ul>
					<? foreach ($this->contentTitles as $contentTitle): ?>
                        <li>
							<?= $contentTitle ?>
                        </li>
					<? endforeach; ?>
                </ul>
			<? endif ?>

        </div>
		<? if (count($this->items) > 0): ?>
            <div id="accordion" class="mb-5">
				<? foreach ($this->items as $item): ?>
                    <div class="card">
                        <!-- panel class must be in -->
                        <a href="#acti_<?= $item->id ?>" data-parent="#accordion" data-toggle="collapse"
                           class="card-header p-2">
                            <h5 class="d-flex align-items-center justify-content-between mb-0">
                                <span class="mr-2"> <?= $item->activity->name ?></span>
                                <span class="badge badge-dark badge small"><?= $item->id ?></span>
                            </h5>
                        </a>
                        <div class="card-body collapse " id="acti_<?= $item->id ?>">
							<? foreach ($this->stravaActivityStreams as $type => $stravaActivityStream): ?>
                                <h5><?= $stravaActivityStream ?> JSON</h5>
                                <pre class="json-viewer border rounded"><?= $item->{$stravaActivityStream . '_json'} ?></pre>
							<? endforeach; ?>

                        </div>
                    </div>

				<? endforeach; ?>
            </div>
		<? endif ?>
    </div>
</div>