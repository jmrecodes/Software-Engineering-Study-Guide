/**
 * Hash Map Implementation from Scratch
 * 
 * Collision Handling Strategy: Chaining with Linked Lists
 * 
 * Time Complexity (Average Case):
 * - Insert: O(1)
 * - Delete: O(1)
 * - Search: O(1)
 * 
 * Time Complexity (Worst Case - all keys hash to same bucket):
 * - Insert: O(n)
 * - Delete: O(n)
 * - Search: O(n)
 * 
 * Space Complexity: O(n)
 * 
 * Load Factor: n / capacity (when > 0.75, resize)
 */

/**
 * Node for chaining in hash table
 */
class HashNode {
    constructor(key, value, next = null) {
        this.key = key;
        this.value = value;
        this.next = next;
    }
}

class HashMap {
    constructor(initialCapacity = 16) {
        this.capacity = initialCapacity;
        this.buckets = new Array(initialCapacity).fill(null);
        this.size = 0;
        this.loadFactorThreshold = 0.75;
    }

    /**
     * Hash function using simple string hashing
     * 
     * Good hash function properties:
     * 1. Deterministic (same key -> same hash)
     * 2. Uniform distribution
     * 3. Fast to compute
     */
    hash(key) {
        let hashCode = 0;
        for (let i = 0; i < key.length; i++) {
            // Multiply by prime number (31) and add char code
            hashCode = (hashCode * 31 + key.charCodeAt(i)) & 0x7FFFFFFF;
        }
        return hashCode % this.capacity;
    }

    /**
     * Insert or update key-value pair
     * Time: O(1) average, O(n) worst case
     */
    put(key, value) {
        // Check if we need to resize
        if (this.size / this.capacity > this.loadFactorThreshold) {
            this.resize();
        }

        const index = this.hash(key);
        let node = this.buckets[index];

        // If bucket is empty, create new node
        if (node === null) {
            this.buckets[index] = new HashNode(key, value);
            this.size++;
            return;
        }

        // Traverse the chain to find key or reach end
        let prev = null;
        while (node !== null) {
            // If key exists, update value
            if (node.key === key) {
                node.value = value;
                return;
            }
            prev = node;
            node = node.next;
        }

        // Key doesn't exist, add to end of chain
        prev.next = new HashNode(key, value);
        this.size++;
    }

    /**
     * Get value by key
     * Time: O(1) average, O(n) worst case
     */
    get(key) {
        const index = this.hash(key);
        let node = this.buckets[index];

        // Traverse chain to find key
        while (node !== null) {
            if (node.key === key) {
                return node.value;
            }
            node = node.next;
        }

        // Key not found
        throw new Error(`Key not found: ${key}`);
    }

    /**
     * Check if key exists
     * Time: O(1) average, O(n) worst case
     */
    has(key) {
        const index = this.hash(key);
        let node = this.buckets[index];

        while (node !== null) {
            if (node.key === key) {
                return true;
            }
            node = node.next;
        }

        return false;
    }

    /**
     * Remove key-value pair
     * Time: O(1) average, O(n) worst case
     */
    delete(key) {
        const index = this.hash(key);
        let node = this.buckets[index];

        if (node === null) {
            return false;
        }

        // Special case: key is in first node
        if (node.key === key) {
            this.buckets[index] = node.next;
            this.size--;
            return true;
        }

        // Find key in chain
        let prev = node;
        node = node.next;
        
        while (node !== null) {
            if (node.key === key) {
                // Remove node by updating previous node's next pointer
                prev.next = node.next;
                this.size--;
                return true;
            }
            prev = node;
            node = node.next;
        }

        return false;
    }

    /**
     * Resize hash table when load factor exceeds threshold
     * This maintains O(1) average time complexity
     * Time: O(n)
     */
    resize() {
        const oldBuckets = this.buckets;
        this.capacity *= 2; // Double the capacity
        this.buckets = new Array(this.capacity).fill(null);
        this.size = 0;

        // Rehash all existing entries
        for (let node of oldBuckets) {
            while (node !== null) {
                this.put(node.key, node.value);
                node = node.next;
            }
        }
    }

    /**
     * Get all keys
     */
    keys() {
        const keys = [];
        for (let node of this.buckets) {
            while (node !== null) {
                keys.push(node.key);
                node = node.next;
            }
        }
        return keys;
    }

    /**
     * Get all values
     */
    values() {
        const values = [];
        for (let node of this.buckets) {
            while (node !== null) {
                values.push(node.value);
                node = node.next;
            }
        }
        return values;
    }

    /**
     * Get current size
     */
    getSize() {
        return this.size;
    }

    /**
     * Get current capacity
     */
    getCapacity() {
        return this.capacity;
    }

    /**
     * Get current load factor
     */
    getLoadFactor() {
        return this.size / this.capacity;
    }

    /**
     * Debug: Show distribution of elements across buckets
     */
    getDistribution() {
        const distribution = {};
        this.buckets.forEach((node, index) => {
            let count = 0;
            let current = node;
            while (current !== null) {
                count++;
                current = current.next;
            }
            if (count > 0) {
                distribution[index] = count;
            }
        });
        return distribution;
    }
}

// Example usage and collision demonstration
if (require.main === module) {
    console.log("=== Hash Map Basic Operations ===");
    const map = new HashMap(4); // Small capacity to demonstrate resizing
    
    map.put("name", "John");
    map.put("age", 30);
    map.put("city", "New York");
    map.put("country", "USA");
    
    console.log("Name:", map.get("name"));
    console.log("Age:", map.get("age"));
    console.log("Size:", map.getSize());
    console.log("Capacity:", map.getCapacity());
    console.log("Load Factor:", map.getLoadFactor().toFixed(2));
    
    console.log("\n=== Collision Handling Demo ===");
    map.put("email", "john@example.com");
    map.put("phone", "123-456-7890");
    
    console.log("Distribution across buckets:", map.getDistribution());
    console.log("All keys:", map.keys());
    
    console.log("\n=== Update and Delete ===");
    map.put("age", 31); // Update
    console.log("Updated age:", map.get("age"));
    
    map.delete("city");
    console.log("Has city:", map.has("city") ? "Yes" : "No");
    console.log("Final size:", map.getSize());
}

module.exports = { HashMap, HashNode };
