package universite_paris8.iut.mcontay.saejeu2d.modele;

import java.util.LinkedList;
import java.util.Queue;

public class BFS {
    private Terrain terrain;

    public BFS(Terrain terrain) {
        this.terrain = terrain;
    }

    public LinkedList<int[]> findPath(int startX, int startY, int endX, int endY) {
        int[][] directions = {{-1, 0}, {1, 0}, {0, -1}, {0, 1}};
        int largeur = 80*16;
        int hauteur = 60*16;
        boolean[][] visited = new boolean[hauteur][largeur];
        int[][] parentX = new int[hauteur][largeur];
        int[][] parentY = new int[hauteur][largeur];
        Queue<int[]> queue = new LinkedList<>();

        queue.add(new int[]{startX, startY});
        visited[startY][startX] = true;
        parentX[startY][startX] = -1;
        parentY[startY][startX] = -1;

        while (!queue.isEmpty()) {
            int[] current = queue.poll();
            int currentX = current[0];
            int currentY = current[1];

            if (currentX == endX && currentY == endY) {
                return reconstructPath(parentX, parentY, endX, endY);
            }

            for (int[] direction : directions) {
                int newX = currentX + direction[0];
                int newY = currentY + direction[1];

                if (newX >= 0 && newY >= 0 && newX < largeur && newY < hauteur && !visited[newY][newX] && terrain.estAutorisee(newX * 32, newY * 32)) {
                    queue.add(new int[]{newX, newY});
                    visited[newY][newX] = true;
                    parentX[newY][newX] = currentX;
                    parentY[newY][newX] = currentY;
                }
            }
        }

        return new LinkedList<>();
    }

    private LinkedList<int[]> reconstructPath(int[][] parentX, int[][] parentY, int endX, int endY) {
        LinkedList<int[]> path = new LinkedList<>();
        for (int x = endX, y = endY; x != -1 && y != -1; ) {
            path.addFirst(new int[]{x, y});
            int tempX = parentX[y][x];
            int tempY = parentY[y][x];
            x = tempX;
            y = tempY;
        }
        return path;
    }
}
