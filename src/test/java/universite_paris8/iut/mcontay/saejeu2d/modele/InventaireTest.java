package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

public class InventaireTest {
    private Inventaire inventaire;
    private Epee epee;
    private Objet objet;
    private Terrain terrain ;

    @BeforeEach
    public void setUp() {
        // Initialisation de l'inventaire et des objets de test
        inventaire = new Inventaire();
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        epee = new Epee("Épée de test", "Description de l'épée", 10, "chemin/image.png", 10, 10);
        objet = new Objet("Objet de test", "Description", terrain, 20, "chemin/image.png",0, 0);
    }

    @Test
    public void testAjouterObjet() {
        // Test de l'ajout d'un objet
        inventaire.ajouterObjet(objet);
        assertTrue(inventaire.contientObjet(objet), "L'objet doit être ajouté à l'inventaire");
    }

    @Test
    public void testRetirerObjet() {
        // Test du retrait d'un objet
        inventaire.ajouterObjet(objet);
        inventaire.retirerObjet(objet);
        assertFalse(inventaire.contientObjet(objet), "L'objet doit être retiré de l'inventaire");
    }

    @Test
    public void testEquiperArme() {
        // Test de l'équipement d'une arme
        inventaire.ajouterObjet(epee);
        inventaire.equiperArme(epee);
        assertEquals(epee, inventaire.getArmeEquipee(), "L'épée doit être équipée");
    }

    @Test
    public void testDesequiperArme() {
        // Test du déséquipement d'une arme
        inventaire.ajouterObjet(epee);
        inventaire.equiperArme(epee);
        inventaire.desequiperArme();
        assertNull(inventaire.getArmeEquipee(), "Aucune arme ne doit être équipée après déséquipement");
    }

    @Test
    public void testGetObjetParNom() {
        // Test de la récupération d'un objet par son nom
        inventaire.ajouterObjet(objet);
        assertEquals(objet, inventaire.getObjetParNom("Objet de test"), "L'objet récupéré doit correspondre à celui ajouté");
    }

    @Test
    public void testToString() {
        // Test de la représentation textuelle de l'inventaire
        inventaire.ajouterObjet(objet);
        String expected = "Inventaire:\n" + objet.toString() + "\n";
        assertEquals(expected, inventaire.toString(), "La représentation textuelle de l'inventaire doit être correcte");
    }
}
