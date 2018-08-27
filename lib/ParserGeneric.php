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
		$startDate = '';
		$this->format->each($this->getTextSheet(), function(array $row) use (&$startDate) {
			if (empty($row['startdate'])) {
				$row['startdate'] = $startDate;
			} else {
				$startDate = $row['startdate'];
			}
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
			$taskChanged = $task['tasknumber'] !== $firstTask['tasknumber'] || 
			               $task['startdate'] !== $firstTask['startdate'];
		} else {
			$taskChanged = !isset($task);
		}
		if ($taskChanged) {
			$tasknumber = $firstTask['tasknumber'];
			$starttime = trim($firstTask['startdate'] . ' ' . $firstTask['starttime']);
			list($descriptions, $duration) = $this->describe();
			if (round($duration) !== 0.0) {
				$duration = number_format($duration / 60, 2, ',', '');
				$this->addTask($tasknumber, $duration, implode(PHP_EOL, $descriptions), $starttime);
			}
			$this->taskQueue = [];
		}
		if (isset($task)) {
			$this->taskQueue[] = $task;
		}
	}
	
	/**
	 * create task descriptions
	 */
	private function describe() {
		$parts = [];
		foreach ($this->taskQueue as $row) {
			$time = $parts[$row['description']] ?? 0;
			$parts[$row['description']] = $time + $row['duration'];
		}
		$duration = 0;
		$descriptions = [];
		$multiline = count($parts) > 1;
		foreach ($parts as $line => $time) {
			$this->formatComment($line);
			$description = $multiline ? '- ' : '';
			$description .= $line;
			if ($multiline) {
				$description .= ' (';
				$hours = floor($time / 60);
				if ($hours > 0) {
					$description .= $hours . 'h';
				}
				$minutes = $time % 60;
				if ($minutes > 0) {
					if ($hours > 0) $description .= ' ';
					$description .= $minutes . 'm';
				}
				$description .= ')';
			}
			$descriptions[] = $description;
			$duration += $time;
		}
		return [$descriptions, $duration];
	}
}
