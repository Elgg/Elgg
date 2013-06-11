<?php
/**
 * Represents graph with data associated with edges. Provides basic graph algorithms.
 *
 * @access private
 * @package Elgg.Core
 */
class Elgg_Graph {

	/**
	 * @var array key is the vertex unique name and value is internal number identyfying it
	 */
	private $vertices = array();

	/**
	 * @var array
	 */
	private $edges = array();

	/**
	 * Adds vertex to graph
	 * 
	 * @param string $name Name of the vertex
	 * @return boolean
	 */
	public function addVertex($name) {
		if (!$this->isVertex($name)) {
			$this->vertices[$name] = count($this->vertices);
			$this->edges[$this->vertices[$name]] = array();
			return true;
		}
		return false;
	}

	/**
	 * Checks if vertex already exists in graph
	 * 
	 * @param boolean $name Name of the vertex
	 * @return boolean
	 */
	public function isVertex($name) {
		return array_key_exists($name, $this->vertices);
	}

	/**
	 * @return string[]
	 */
	public function getVertices() {
		return array_keys($this->vertices);
	}

	/**
	 * Adds edge to graph. Adds vertices if not added yet
	 * 
	 * @param string $vFromName Name of source vertex
	 * @param string $vToName   Name of target vertex
	 * @param mixed  $data      Optional data to be stored with edge
	 * @return boolean
	 */
	public function addEdge($vFromName, $vToName, $data = null) {
		if (!$this->isVertex($vFromName)) {
			$this->addVertex($vFromName);
		}
		if (!$this->isVertex($vToName)) {
			$this->addVertex($vToName);
		}
		if (!array_key_exists($vToName, $this->edges[$this->vertices[$vFromName]])) {
			$this->edges[$this->vertices[$vFromName]][$this->vertices[$vToName]] = $data;
			return true;
		}
		return false;
	}

	/**
	 * Returns array of arrays containing three values: fromName, toName and data
	 * 
	 * @return array 
	 */
	public function getEdges() {
		$result = array();
		$map = array_flip($this->vertices);
		foreach ($this->edges as $fromName => $list) {
			foreach ($list as $toName => $data) {
				$result[] = array($map[$fromName], $map[$toName], $data);
			}
		}
		return $result;
	}

	/*
	 * Algorithms
	*/

	/**
	 * @var array colors of vertices used by DFS algorithm
	 */
	private $dfsColors;

	/**
	 * @var array of parents in DFS algorithm predcessor tree
	 */
	private $dfsPi;

	private $dfsTime;

	private $dfsEntryTimes;

	private $dfsExitTimes;

	/**
	 * @var array|null|boolean false when no cycle was found; array of names of vertices in one of
	 * the cycles or true when cycle exists; null when no DFS check was performed yet
	 */
	private $dfsCycle = null;

	/**
	 * @var int color used by DFS algorithm
	 */
	const DFS_COLOR_WHITE = 0;

	/**
	 * @var int color used by DFS algorithm
	 */
	const DFS_COLOR_GRAY = 1;

	/**
	 * @var int color used by DFS algorithm
	 */
	const DFS_COLOR_BLACK = 2;

	/**
	 * Performs depth-first search algorithm on current graph
	 * 
	 * @param boolean $catchTimes Should algorithm compute enter and exit times for vertices
	 * @param boolean $catchCycle Should algorithm keep data to reconstruct cycle if found in the graph
	 * @return null
	 */
	public function dfs($catchTimes = false, $catchCycle = false) {
		$this->dfsColors = array();
		foreach ($this->vertices as $vertex) {
			$this->dfsColors[$vertex] = self::DFS_COLOR_WHITE;
		}
		$this->dfsCycle = null;
		$this->dfsTime = 0;
		$this->dfsEntryTimes = array();
		$this->dfsExitTimes = array();
		if ($catchCycle) {
			$this->dfsPi = array();
			foreach ($this->vertices as $vertex) {
				$this->dfsPi[$vertex] = null;
			}
		}
		foreach ($this->vertices as $vertex) {
			if ($this->dfsColors[$vertex] == self::DFS_COLOR_WHITE) {
				$this->dfsVisit($vertex, $catchTimes, $catchCycle);
			}
		}
		if ($this->dfsCycle === null) {
			$this->dfsCycle = false;
		}
	}

	/**
	 * Visits recursively selected vertex. It's part of implementation of DFS algorithm
	 * 
	 * @param int     $vertex     Name of the vertex
	 * @param boolean $catchTimes Should algorithm compute enter and exit times for vertices
	 * @param boolean $catchCycle Should algorithm keep data to reconstruct cycle if found in the graph
	 * @return null
	 */
	protected function dfsVisit($vertex, $catchTimes, $catchCycle) {
		$this->dfsColors[$vertex] = self::DFS_COLOR_GRAY;
		if ($catchTimes) {
			$this->dfsTime++;
			$this->dfsEntryTimes[$vertex] = $this->dfsTime;
		}
		foreach ($this->edges[$vertex] as $toName => $data) {
			if ($this->dfsColors[$toName] == self::DFS_COLOR_WHITE) {
				if ($catchCycle) {
					$this->dfsPi[$toName] = $vertex;
				}
				$this->dfsVisit($toName, $catchTimes, $catchCycle);
			} elseif ($this->dfsColors[$toName] == self::DFS_COLOR_GRAY) {
				//mark cycle if not found yet
				if ($this->dfsCycle === null) {
					if ($catchCycle) {
						//reconstruct the cycle
						$map = array_flip($this->vertices);
						$curr = $vertex;
						$this->dfsCycle = array($map[$curr]);
						while (isset($this->dfsPi[$curr]) && $curr != $toName) {
							$curr = $this->dfsPi[$curr];
							$this->dfsCycle[] = $map[$curr];
						}
					} else {
						$this->dfsCycle = true;
					}
				}
			} else {
				//black vertex can't be part of new cycle as we'd have to come from it and unchecked trees are marked gray
				if ($catchCycle) {
					//connect paths coming into child black subtree
					$this->dfsPi[$toName] = $vertex;
				}
			}
		}
		$this->dfsColors[$vertex] = self::DFS_COLOR_BLACK;
		if ($catchTimes) {
			$this->dfsTime++;
			$this->dfsExitTimes[$vertex] = $this->dfsTime;
		}
	}

	/**
	 * Sorts graph topologically
	 * 
	 * @param boolean $catchCycle Should algorithm keep data to reconstruct cycle if found in the graph
	 * @return boolean
	 */
	public function topologicalSort($catchCycle = false) {
		$this->dfs(true, $catchCycle);
		if (!$this->isAcyclic()) {
			return false;
		}
		//sort descending by exit times
		$list = $this->dfsExitTimes;
		arsort($list);
		//resolve vertices names
		$map = array_flip($this->vertices);
		$result = array();
		foreach ($list as $vertex => $time) {
			$result[] = $map[$vertex];
		}
		return $result;
	}

	/**
	 * Tells if current graph does not contain any cycle
	 * 
	 * @return boolean
	 */
	public function isAcyclic() {
		return $this->getCycle() === false;
	}

	/**
	 * Returns graph cycle if exists
	 * 
	 * @return array|false returns array of names of vertices making the cycle or false if graph is acyclic
	 */
	public function getCycle() {
		if ($this->dfsCycle === null) {
			$this->dfs(false, true);
		}
		return $this->dfsCycle;
	}

}
