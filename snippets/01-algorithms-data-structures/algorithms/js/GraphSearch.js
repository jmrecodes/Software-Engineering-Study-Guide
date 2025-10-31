/**
 * Graph Representation using Adjacency List
 * 
 * Adjacency List: Map where each vertex stores a list of adjacent vertices
 * 
 * Advantages:
 * - Space efficient: O(V + E)
 * - Fast iteration over neighbors
 * 
 * This implementation supports both directed and undirected graphs
 */
class Graph {
    constructor(directed = false) {
        this.adjacencyList = new Map();
        this.directed = directed;
    }

    /**
     * Add a vertex to the graph
     */
    addVertex(vertex) {
        if (!this.adjacencyList.has(vertex)) {
            this.adjacencyList.set(vertex, []);
        }
    }

    /**
     * Add an edge between two vertices
     */
    addEdge(from, to) {
        // Ensure both vertices exist
        this.addVertex(from);
        this.addVertex(to);

        // Add edge
        this.adjacencyList.get(from).push(to);

        // If undirected, add reverse edge
        if (!this.directed) {
            this.adjacencyList.get(to).push(from);
        }
    }

    /**
     * Get all neighbors of a vertex
     */
    getNeighbors(vertex) {
        return this.adjacencyList.get(vertex) || [];
    }

    /**
     * Get all vertices
     */
    getVertices() {
        return Array.from(this.adjacencyList.keys());
    }

    /**
     * Display the graph
     */
    display() {
        for (const [vertex, neighbors] of this.adjacencyList) {
            console.log(`${vertex} -> ${neighbors.join(', ')}`);
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
class BFS {
    /**
     * Perform BFS traversal from a starting vertex
     * Returns array of vertices in BFS order
     */
    static traverse(graph, start) {
        const visited = new Set();     // Track visited vertices
        const queue = [start];         // Queue for BFS (FIFO)
        const result = [];             // Store traversal order
        visited.add(start);

        while (queue.length > 0) {
            // Dequeue: remove first element
            const vertex = queue.shift();
            result.push(vertex);

            // Visit all neighbors
            for (const neighbor of graph.getNeighbors(vertex)) {
                if (!visited.has(neighbor)) {
                    visited.add(neighbor);
                    queue.push(neighbor); // Enqueue neighbor
                }
            }
        }

        return result;
    }

    /**
     * Find shortest path between two vertices (unweighted)
     * Returns path as array, or null if no path exists
     */
    static shortestPath(graph, start, end) {
        const visited = new Set([start]);
        const queue = [[start]]; // Queue of paths
        
        while (queue.length > 0) {
            const path = queue.shift();
            const vertex = path[path.length - 1];
            
            // Found the destination
            if (vertex === end) {
                return path;
            }
            
            // Explore neighbors
            for (const neighbor of graph.getNeighbors(vertex)) {
                if (!visited.has(neighbor)) {
                    visited.add(neighbor);
                    queue.push([...path, neighbor]);
                }
            }
        }
        
        return null; // No path found
    }

    /**
     * BFS with level tracking
     * Returns object mapping each vertex to its level from start
     */
    static levels(graph, start) {
        const levels = { [start]: 0 };
        const queue = [start];
        
        while (queue.length > 0) {
            const vertex = queue.shift();
            const currentLevel = levels[vertex];
            
            for (const neighbor of graph.getNeighbors(vertex)) {
                if (!(neighbor in levels)) {
                    levels[neighbor] = currentLevel + 1;
                    queue.push(neighbor);
                }
            }
        }
        
        return levels;
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
class DFS {
    /**
     * Perform DFS traversal (Recursive)
     * Returns array of vertices in DFS order
     */
    static traverse(graph, start) {
        const visited = new Set();
        const result = [];
        this.dfsRecursive(graph, start, visited, result);
        return result;
    }

    /**
     * Recursive DFS helper
     */
    static dfsRecursive(graph, vertex, visited, result) {
        // Mark as visited and add to result
        visited.add(vertex);
        result.push(vertex);

        // Visit all unvisited neighbors
        for (const neighbor of graph.getNeighbors(vertex)) {
            if (!visited.has(neighbor)) {
                this.dfsRecursive(graph, neighbor, visited, result);
            }
        }
    }

    /**
     * Iterative DFS using explicit stack
     * More control, can be easier to modify
     */
    static traverseIterative(graph, start) {
        const visited = new Set();
        const stack = [start];
        const result = [];

        while (stack.length > 0) {
            // Pop from stack (LIFO)
            const vertex = stack.pop();

            if (!visited.has(vertex)) {
                visited.add(vertex);
                result.push(vertex);

                // Push all unvisited neighbors
                // Note: reverse order to match recursive DFS
                const neighbors = [...graph.getNeighbors(vertex)].reverse();
                for (const neighbor of neighbors) {
                    if (!visited.has(neighbor)) {
                        stack.push(neighbor);
                    }
                }
            }
        }

        return result;
    }

    /**
     * Find a path between two vertices using DFS
     * Returns first path found (not necessarily shortest)
     */
    static findPath(graph, start, end) {
        const visited = new Set();
        const path = [];
        
        if (this.dfsPath(graph, start, end, visited, path)) {
            return path;
        }
        
        return null;
    }

    static dfsPath(graph, current, end, visited, path) {
        visited.add(current);
        path.push(current);

        // Found destination
        if (current === end) {
            return true;
        }

        // Explore neighbors
        for (const neighbor of graph.getNeighbors(current)) {
            if (!visited.has(neighbor)) {
                if (this.dfsPath(graph, neighbor, end, visited, path)) {
                    return true;
                }
            }
        }

        // Backtrack: remove current vertex from path
        path.pop();
        return false;
    }

    /**
     * Detect cycle in graph using DFS
     * Works for both directed and undirected graphs
     */
    static hasCycle(graph) {
        const visited = new Set();
        const recursionStack = new Set();

        for (const vertex of graph.getVertices()) {
            if (!visited.has(vertex)) {
                if (this.hasCycleUtil(graph, vertex, visited, recursionStack, null)) {
                    return true;
                }
            }
        }

        return false;
    }

    static hasCycleUtil(graph, vertex, visited, recursionStack, parent) {
        visited.add(vertex);
        recursionStack.add(vertex);

        for (const neighbor of graph.getNeighbors(vertex)) {
            if (!visited.has(neighbor)) {
                if (this.hasCycleUtil(graph, neighbor, visited, recursionStack, vertex)) {
                    return true;
                }
            } else if (recursionStack.has(neighbor) && neighbor !== parent) {
                // Found back edge (cycle)
                return true;
            }
        }

        recursionStack.delete(vertex);
        return false;
    }
}

// Example usage and demonstrations
if (require.main === module) {
    console.log("=== Graph Creation ===");
    const graph = new Graph(false); // Undirected graph
    
    // Create a simple graph
    //     A --- B
    //     |     |
    //     C --- D --- E
    
    graph.addEdge('A', 'B');
    graph.addEdge('A', 'C');
    graph.addEdge('B', 'D');
    graph.addEdge('C', 'D');
    graph.addEdge('D', 'E');
    
    console.log("Graph structure:");
    graph.display();

    console.log("\n=== BFS Traversal ===");
    const bfsResult = BFS.traverse(graph, 'A');
    console.log("BFS from A:", bfsResult.join(' -> '));

    console.log("\n=== BFS Shortest Path ===");
    const path = BFS.shortestPath(graph, 'A', 'E');
    console.log("Shortest path A to E:", path.join(' -> '));

    console.log("\n=== BFS Levels ===");
    const levels = BFS.levels(graph, 'A');
    for (const [vertex, level] of Object.entries(levels)) {
        console.log(`${vertex} is at level ${level}`);
    }

    console.log("\n=== DFS Traversal (Recursive) ===");
    const dfsResult = DFS.traverse(graph, 'A');
    console.log("DFS from A:", dfsResult.join(' -> '));

    console.log("\n=== DFS Traversal (Iterative) ===");
    const dfsIterative = DFS.traverseIterative(graph, 'A');
    console.log("DFS (iterative) from A:", dfsIterative.join(' -> '));

    console.log("\n=== DFS Path Finding ===");
    const dfsPath = DFS.findPath(graph, 'A', 'E');
    console.log("DFS path A to E:", dfsPath.join(' -> '));

    console.log("\n=== Cycle Detection ===");
    console.log("Has cycle:", DFS.hasCycle(graph) ? "Yes" : "No");
    
    // Add cycle
    graph.addEdge('E', 'A');
    console.log("After adding E->A edge:", DFS.hasCycle(graph) ? "Yes" : "No");
}

module.exports = { Graph, BFS, DFS };
