package universite_paris8.iut.mcontay.saejeu2d.util;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import java.io.IOException;
import java.net.URL;
import java.util.HashMap;
import java.util.Map;

/* La classe JsonLoader charge un fichier JSON spécifié, le parse en utilisant Jackson, et extrait les données des couches de tuiles (tilelayer) en les stockant dans une map.
 Le constructeur initialise rootNode en lisant le fichier JSON, et la méthode getLayers parcourt les couches pour récupérer les données de tuiles.
 La méthode close est implémentée mais inutile ici car aucune ressource n'a besoin d'être fermée. */

public class JsonLoader implements AutoCloseable {
    private JsonNode rootNode;
    private ObjectMapper objectMapper = new ObjectMapper();

    public JsonLoader(String jsonFilePath) throws IOException {
        URL file = Lanceur.class.getResource(jsonFilePath);
        if (file == null) {
            throw new IOException("(JsonLoader) Le fichier n'existe pas : " + jsonFilePath);
        }
        rootNode = objectMapper.readTree(file);
    }

    public Map<String, int[]> getLayers() {
        Map<String, int[]> result = new HashMap<>();
        JsonNode layers = rootNode.path("layers");

        for (JsonNode layer : layers) {
            if ("tilelayer".equals(layer.get("type").asText())) {
                JsonNode data = layer.get("data");
                int[] tiles = new int[data.size()];

                for (int i = 0; i < data.size(); i++) {
                    tiles[i] = data.get(i).asInt();
                }
                result.put(layer.get("name").asText(), tiles);
            }
        }

        return result;
    }

    @Override
    public void close() {
        // No resources to close in this example
    }
}

