package universite_paris8.iut.mcontay.saejeu2d.util;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;

import java.io.File;
import java.io.IOException;
import java.net.URL;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class JsonLoader implements AutoCloseable {
    private JsonNode rootNode;

    public JsonLoader(String jsonFilePath) throws IOException {
        ObjectMapper objectMapper = new ObjectMapper();
        URL file = Lanceur.class.getResource(jsonFilePath);
        if (file == null) {
            throw new IOException("(JsonLoader) Le fichier n'existe pas : " + jsonFilePath);
        }
        rootNode = objectMapper.readTree(file);
    }

    public Map<String, int[]> getLayers() {
        Map<String, int[]> result = new HashMap<>();
        JsonNode layers = rootNode.path("layers");


        for (JsonNode layer : layers){
            if (layer.get("type").asText().equals("tilelayer")){
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
        // No resources to close in this example, but required by AutoCloseable
    }
}
