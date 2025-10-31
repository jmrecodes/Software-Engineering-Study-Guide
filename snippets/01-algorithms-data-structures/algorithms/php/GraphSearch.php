<?php

declare(strict_types=1);

/**
 * Graph Representation using Adjacency List
 * 
 * Adjacency List: Array/Map where each vertex stores a list of adjacent vertices
 * 
 * Advantages:
 * - Space efficient: O(V + E)
 * - Fast iteration over neighbors
 * 
 * This implementation supports both directed and undirected graphs
 */
class Graph
{
    private array $adjacencyList = [];
    private bool $directed;

    public function __construct(bool $directed = false)
    {
        $this->directed = $directed;
    }

    /**
     * Add a vertex to the graph
     */
    public function addVertex(string $vertex): void
    {
        if (!isset($this->adjacencyList[$vertex])) {
            $this->adjacencyList[$vertex] = [];
        }
    }

    /**
     * Add an edge between two vertices
     */
    public function addEdge(string $from, string $to): void
    {
        // Ensure both vertices exist
        $this->addVertex($from);
        $this->addVertex($to);

        // Add edge
        $this->adjacencyList[$from][] = $to;

        // If undirected, add reverse edge
        if (!$this->directed) {
            $this->adjacencyList[$to][] = $from;
        }
    }

    /**
     * Get all neighbors of a vertex
     */
    public function getNeighbors(string $vertex): array
    {
        return $this->adjacencyList[$vertex] ?? [];
    }

    /**
     * Get all vertices
     */
    public function getVertices(): array
    {
        return array_keys($this->adjacencyList);
    }

    /**
     * Display the graph
     */
    public function display(): void
    {
        foreach ($this->adjacencyList as $vertex => $neighbors) {
            echo "$vertex -> " . implode(', ', $neighbors) . "\n";
        }
    }
}

/**
 * Breadth-First Search (BFS)
 * 
 * Strategy: Explore level by level (uses Queue)
 * 
 * Time Complexity: O(V + E) where V = vertices, E = edges
 * Space Complexity: O(V) for queue and visited set
 * 
 * Use Cases:
 * - Shortest path in unweighted graph
 * - Level-order traversal
 * - Finding connected components
 * - Web crawling
 */
class BFS
{
    /**
     * Perform BFS traversal from a starting vertex
     * Returns array of vertices in BFS order
     */
    public static function traverse(Graph $graph, string $start): array
    {
        $visited = [];           // Track visited vertices
        $queue = [$start];       // Queue for BFS (FIFO)
        $result = [];            // Store traversal order
        $visited[$start] = true;

        while (!empty($queue)) {
            // Dequeue: remove first element
            $vertex = array_shift($queue);
            $result[] = $vertex;

            // Visit all neighbors
            foreach ($graph->getNeighbors($vertex) as $neighbor) {
                if (!isset($visited[$neighbor])) {
                    $visited[$neighbor] = true;
                    $queue[] = $neighbor; // Enqueue neighbor
                }
            }
        }

        return $result;
    }

    /**
     * Find shortest path between two vertices (unweighted)
     * Returns path as array, or null if no path exists
     */
    public static function shortestPath(Graph $graph, string $start, string $end): ?array
    {
        $visited = [$start => true];
        $queue = [[$start]]; // Queue of paths
        
        while (!empty($queue)) {
            $path = array_shift($queue);
            $vertex = end($path);
            
            // Found the destination
            if ($vertex === $end) {
                return $path;
            }
            
            // Explore neighbors
            foreach ($graph->getNeighbors($vertex) as $neighbor) {
                if (!isset($visited[$neighbor])) {
                    $visited[$neighbor] = true;
                    $newPath = $path;
                    $newPath[] = $neighbor;
                    $queue[] = $newPath;
                }
            }
        }
        
        return null; // No path found
    }

    /**
     * BFS with level tracking
     * Returns array mapping each vertex to its level from start
     */
    public static function levels(Graph $graph, string $start): array
    {
        $levels = [$start => 0];
        $queue = [$start];
        
        while (!empty($queue)) {
            $vertex = array_shift($queue);
            $currentLevel = $levels[$vertex];
            
            foreach ($graph->getNeighbors($vertex) as $neighbor) {
                if (!isset($levels[$neighbor])) {
                    $levels[$neighbor] = $currentLevel + 1;
                    $queue[] = $neighbor;
                }
            }
        }
        
        return $levels;
    }
}

/**
 * Depth-First Search (DFS)
 * 
 * Strategy: Explore as deep as possible before backtracking (uses Stack/Recursion)
 * 
 * Time Complexity: O(V + E)
 * Space Complexity: O(V) for recursion stack and visited set
 * 
 * Use Cases:
 * - Cycle detection
 * - Topological sorting
 * - Finding strongly connected components
 * - Solving puzzles (maze, sudoku)
 * - Pathfinding (not guaranteed to be shortest)
 */
class DFS
{
    /**
     * Perform DFS traversal (Recursive)
     * Returns array of vertices in DFS order
     */
    public static function traverse(Graph $graph, string $start): array
    {
        $visited = [];
        $result = [];
        self::dfsRecursive($graph, $start, $visited, $result);
        return $result;
    }

    /**
     * Recursive DFS helper
     */
    private static function dfsRecursive(Graph $graph, string $vertex, array &$visited, array &$result): void
    {
        // Mark as visited and add to result
        $visited[$vertex] = true;
        $result[] = $vertex;

        // Visit all unvisited neighbors
        foreach ($graph->getNeighbors($vertex) as $neighbor) {
            if (!isset($visited[$neighbor])) {
                self::dfsRecursive($graph, $neighbor, $visited, $result);
            }
        }
    }

    /**
     * Iterative DFS using explicit stack
     * More control, can be easier to modify
     */
    public static function traverseIterative(Graph $graph, string $start): array
    {
        $visited = [];
        $stack = [$start];
        $result = [];

        while (!empty($stack)) {
            // Pop from stack (LIFO)
            $vertex = array_pop($stack);

            if (!isset($visited[$vertex])) {
                $visited[$vertex] = true;
                $result[] = $vertex;

                // Push all unvisited neighbors
                // Note: reverse order to match recursive DFS
                $neighbors = array_reverse($graph->getNeighbors($vertex));
                foreach ($neighbors as $neighbor) {
                    if (!isset($visited[$neighbor])) {
                        $stack[] = $neighbor;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Find a path between two vertices using DFS
     * Returns first path found (not necessarily shortest)
     */
    public static function findPath(Graph $graph, string $start, string $end): ?array
    {
        $visited = [];
        $path = [];
        
        if (self::dfsPath($graph, $start, $end, $visited, $path)) {
            return $path;
        }
        
        return null;
    }

    private static function dfsPath(Graph $graph, string $current, string $end, array &$visited, array &$path): bool
    {
        $visited[$current] = true;
        $path[] = $current;

        // Found destination
        if ($current === $end) {
            return true;
        }

        // Explore neighbors
        foreach ($graph->getNeighbors($current) as $neighbor) {
            if (!isset($visited[$neighbor])) {
                if (self::dfsPath($graph, $neighbor, $end, $visited, $path)) {
                    return true;
                }
            }
        }

        // Backtrack: remove current vertex from path
        array_pop($path);
        return false;
    }

    /**
     * Detect cycle in graph using DFS
     * Works for both directed and undirected graphs
     */
    public static function hasCycle(Graph $graph): bool
    {
        $visited = [];
        $recursionStack = [];

        foreach ($graph->getVertices() as $vertex) {
            if (!isset($visited[$vertex])) {
                if (self::hasCycleUtil($graph, $vertex, $visited, $recursionStack, null)) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function hasCycleUtil(Graph $graph, string $vertex, array &$visited, array &$recursionStack, ?string $parent): bool
    {
        $visited[$vertex] = true;
        $recursionStack[$vertex] = true;

        foreach ($graph->getNeighbors($vertex) as $neighbor) {
            if (!isset($visited[$neighbor])) {
                if (self::hasCycleUtil($graph, $neighbor, $visited, $recursionStack, $vertex)) {
                    return true;
                }
            } elseif (isset($recursionStack[$neighbor]) && $neighbor !== $parent) {
                // Found back edge (cycle)
                return true;
            }
        }

        unset($recursionStack[$vertex]);
        return false;
    }
}

// Example usage and demonstrations
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    echo "=== Graph Creation ===\n";
    $graph = new Graph(false); // Undirected graph
    
    // Create a simple graph
    //     A --- B
    //     |     |
    //     C --- D --- E
    
    $graph->addEdge('A', 'B');
    $graph->addEdge('A', 'C');
    $graph->addEdge('B', 'D');
    $graph->addEdge('C', 'D');
    $graph->addEdge('D', 'E');
    
    echo "Graph structure:\n";
    $graph->display();

    echo "\n=== BFS Traversal ===\n";
    $bfsResult = BFS::traverse($graph, 'A');
    echo "BFS from A: " . implode(' -> ', $bfsResult) . "\n";

    echo "\n=== BFS Shortest Path ===\n";
    $path = BFS::shortestPath($graph, 'A', 'E');
    echo "Shortest path A to E: " . implode(' -> ', $path) . "\n";

    echo "\n=== BFS Levels ===\n";
    $levels = BFS::levels($graph, 'A');
    foreach ($levels as $vertex => $level) {
        echo "$vertex is at level $level\n";
    }

    echo "\n=== DFS Traversal (Recursive) ===\n";
    $dfsResult = DFS::traverse($graph, 'A');
    echo "DFS from A: " . implode(' -> ', $dfsResult) . "\n";

    echo "\n=== DFS Traversal (Iterative) ===\n";
    $dfsIterative = DFS::traverseIterative($graph, 'A');
    echo "DFS (iterative) from A: " . implode(' -> ', $dfsIterative) . "\n";

    echo "\n=== DFS Path Finding ===\n";
    $dfsPath = DFS::findPath($graph, 'A', 'E');
    echo "DFS path A to E: " . implode(' -> ', $dfsPath) . "\n";

    echo "\n=== Cycle Detection ===\n";
    echo "Has cycle: " . (DFS::hasCycle($graph) ? "Yes" : "No") . "\n";
    
    // Add cycle
    $graph->addEdge('E', 'A');
    echo "After adding E->A edge: " . (DFS::hasCycle($graph) ? "Yes" : "No") . "\n";
}
