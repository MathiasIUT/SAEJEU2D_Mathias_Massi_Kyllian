package universite_paris8.iut.mcontay.saejeu2d.modele;

import universite_paris8.iut.mcontay.saejeu2d.util.JsonLoader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Map;

public class Terrain {

    private int[][] solLayer;
    private int[][] decoLayer;
    private List<Integer> nonPraticables;

    public Terrain(String jsonFilePath) {
        this.nonPraticables = new ArrayList<>();
        try (JsonLoader jsonLoader = new JsonLoader(jsonFilePath)) {
            Map<String, int[]> layers = jsonLoader.getLayers();
            solLayer = convertTo2DArray(layers.get("Sol"), 60, 80);
            decoLayer = convertTo2DArray(layers.get("Deco"), 60, 80);
        } catch (IOException e) {
            System.err.println("(Terrain) Erreur de lecture du fichier JSON  : " + e.getMessage());
        }
        assert this.nonPraticables != null;
        this.nonPraticables.addAll(Arrays.asList(8,9,10,11,12,13,23,24,25,26,27,36, 37,38,39,40,41,51,52,53,54,55,62,65,66,67,68,69,79,80,81,91,92,93,95, 96,97,104,105,107,108,109,110,119,120,123,124,125,127,132,133,135,136,137,146, 147,150,152,153,163,164,165,169,171,172,173,174,175,176,177,178,182,183,184,185,186,187,188,189,190,191,192,193,197,199,202,203,204,205,206,212,215,219,220,225,226,227,228,229,230,231,238,239,240,241,242,243,246,247,249,253,255,256,257,259,278,281,283,286,287,288,289,290,294,295,296,297,298,299,300,301,309,310,311,315,316,317,322,323,324,325,326,327,331,332,337,338,339,343,344,345,350,351,352,354,355,359,360,361,365,367,371,372,373,374,378,379,380,381,382,393,395,399,400,401));
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
    if (solLayer == null || decoLayer == null) {
        return false;
    }
    int tileX = (int) (y / 16);
    int tileY = (int) (x / 16);
    if (tileX < 0 || tileX >= solLayer.length || tileY < 0 || tileY >= solLayer[0].length) {
        return false;
    }
//    System.out.println(nonPraticables);
//    System.out.println(decoLayer[tileX][tileY]);
    if (nonPraticables.contains(decoLayer[tileX][tileY]) || nonPraticables.contains(solLayer[tileX][tileY])) {
        System.out.println("impossible de se déplacer ici");
        return false;
    } else if (x >= 0 && x < solLayer[0].length * 16 && y >= 0 && y < solLayer.length * 16) {
        return true;
    }
    return false;
}
}
