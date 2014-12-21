<?php

/**
 * Class ParserGeneric
 *
 * @property $format DataFormat
 */
class ParserGeneric extends ParserAbstract {

	/**
	 * @var array
	 */
	private $taskQueue = [];

	/**
	 * Finds out if the parser understands format of the given data
	 *
	 * @param string $sheet
	 * @return mixed
	 */
	public static function canParse($sheet) {
		return DataFormat::guess($sheet);
	}

	/**
	 * parse the sheet
	 */
	protected function parse() {
		$this->format->each($this->getTextSheet(), function(array $row) {
			$this->queueTask($row);
		});
		$this->queueTask();
	}

	/**
	 * memorize task for summarization / add task when number changes
	 */
	private function queueTask(array $task = null) {
		$firstTask = empty($this->taskQueue) ? null : reset($this->taskQueue);
		if (isset($task, $firstTask)) {
			$taskChanged = $task['tasknumber'] !== $firstTask['tasknumber'];
		} else {
			$taskChanged = !isset($task);
		}
		if ($taskChanged) {
			$tasknumber = $firstTask['tasknumber'];
			$starttime = $firstTask['starttime'];
			$duration = 0;
			$descriptions = [];
			foreach ($this->taskQueue as $row) {
				$this->formatComment($row['description']);
				$descriptions[] = "- $row[description]";
				$duration += $row['duration'];
			}
			$duration = number_format($duration / 60, 2, ',', '');
			$this->addTask($tasknumber, $duration, implode(PHP_EOL, $descriptions), $starttime);
			$this->taskQueue = [];
		}
		if (isset($task)) {
			$this->taskQueue[] = $task;
		}
	}
}
