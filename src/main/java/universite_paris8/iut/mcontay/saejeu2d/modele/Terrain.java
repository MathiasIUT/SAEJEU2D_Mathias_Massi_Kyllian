package universite_paris8.iut.mcontay.saejeu2d.modele;

import universite_paris8.iut.mcontay.saejeu2d.util.JsonLoader;

import java.io.IOException;
import java.util.Map;

public class Terrain {
    private int[][] solLayer;
    private int[][] decoLayer;

    public Terrain(String jsonFilePath) {
        try (JsonLoader jsonLoader = new JsonLoader(jsonFilePath)) {
            Map<String, int[]> layers = jsonLoader.getLayers();


            solLayer = convertTo2DArray(layers.get("Sol"), 60, 80);
            decoLayer = convertTo2DArray(layers.get("Deco"), 60, 80);
        } catch (IOException e) {
            System.err.println("(Terrain) Erreur de lecture du fichier JSON  : " + e.getMessage());
        }
    }

    public int[][] getSolLayer() {
        return solLayer;
    }

    public int[][] getDecoLayer() {
        return decoLayer;
    }


    private int[][] convertTo2DArray(int[] array, int rows, int cols) {

        if (array == null) {
            return new int[rows][cols]; // Return an empty array if null
        }
        int[][] result = new int[rows][cols];
        for (int i = 0; i < array.length; i++) {
            result[i / cols][i % cols] = array[i];
        }
        return result;
    }

public boolean estAutorisee(double x, double y) {
        if (solLayer == null) {
            return false;
        }
        int tileX = (int) (y / 16);
        int tileY = (int) (x / 16);
        if (tileX < 0 || tileX >= solLayer.length || tileY < 0 || tileY >= solLayer[0].length) {
            return false;
        }
        if (decoLayer[tileX][tileY] == 3) {
            System.out.println("impossible");
            return false;
        } else if (x >= 0 && x < solLayer[0].length * 16 && y >= 0 && y < solLayer.length * 16) {
            return true;
        }
        return false;
    }
}
