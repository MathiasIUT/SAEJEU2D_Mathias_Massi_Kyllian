package universite_paris8.iut.mcontay.saejeu2d.util;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.List;

public class TileMap {
    @JsonProperty("compressionlevel")
    private int compressionLevel;
    private int height;
    private boolean infinite;
    private List<Layer> layers;
    private int nextlayerid;
    private int nextobjectid;
    private String orientation;
    private String renderorder;
    private String tiledversion;
    private int tileheight;
    private List<Tileset> tilesets;
    private int tilewidth;
    private String type;
    private String version;
    private int width;

    // Getters and setters...

    public int getCompressionLevel() {
        return compressionLevel;
    }

    public void setCompressionLevel(int compressionLevel) {
        this.compressionLevel = compressionLevel;
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public boolean isInfinite() {
        return infinite;
    }

    public void setInfinite(boolean infinite) {
        this.infinite = infinite;
    }

    public List<Layer> getLayers() {
        return layers;
    }

    public void setLayers(List<Layer> layers) {
        this.layers = layers;
    }

    public int getNextlayerid() {
        return nextlayerid;
    }

    public void setNextlayerid(int nextlayerid) {
        this.nextlayerid = nextlayerid;
    }

    public int getNextobjectid() {
        return nextobjectid;
    }

    public void setNextobjectid(int nextobjectid) {
        this.nextobjectid = nextobjectid;
    }

    public String getOrientation() {
        return orientation;
    }

    public void setOrientation(String orientation) {
        this.orientation = orientation;
    }

    public String getRenderorder() {
        return renderorder;
    }

    public void setRenderorder(String renderorder) {
        this.renderorder = renderorder;
    }

    public String getTiledversion() {
        return tiledversion;
    }

    public void setTiledversion(String tiledversion) {
        this.tiledversion = tiledversion;
    }

    public int getTileheight() {
        return tileheight;
    }

    public void setTileheight(int tileheight) {
        this.tileheight = tileheight;
    }

    public List<Tileset> getTilesets() {
        return tilesets;
    }

    public void setTilesets(List<Tileset> tilesets) {
        this.tilesets = tilesets;
    }

    public int getTilewidth() {
        return tilewidth;
    }

    public void setTilewidth(int tilewidth) {
        this.tilewidth = tilewidth;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getVersion() {
        return version;
    }

    public void setVersion(String version) {
        this.version = version;
    }

    public int getWidth() {
        return width;
    }

    public void setWidth(int width) {
        this.width = width;
    }
}

