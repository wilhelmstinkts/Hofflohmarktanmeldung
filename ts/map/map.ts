import { Feature, Map, View } from 'ol';
import OSM from 'ol/source/OSM';
import TileLayer from 'ol/layer/Tile';
import Zoom from 'ol/control/Zoom';
import Attribution from 'ol/control/Attribution';
import { useGeographic } from 'ol/proj';
import Vector from 'ol/layer/Vector';
import SourceVector from 'ol/source/Vector';
import Style from 'ol/style/Style';
import Text from 'ol/style/Text';
import CircleStyle from 'ol/style/Circle';
import { Point } from 'ol/geom';
import Stroke from 'ol/style/Stroke';
import Rotate from 'ol/control/Rotate';
import Fill from 'ol/style/Fill';

const mapCenter = { "latitude": 52.5880115, "longitude": 13.367 };
const defaultZoomLevel = 14.5;

export const drawMap = ({ containerId, attributionId, markers }: { containerId: string, attributionId?: string, markers: { lon: number, lat: number }[] }) => {
    useGeographic();

    const map = new Map(
        {
            target: containerId,
            layers: [
                new TileLayer(
                    { source: new OSM() })
            ],
            view: new View({
                center: [mapCenter.longitude, mapCenter.latitude],
                zoom: defaultZoomLevel
            }),
            controls: [
                new Rotate({}),
                new Attribution({
                    target: attributionId,
                    className: 'mapAttribution'
                }),
                new Zoom()                
            ]
        }
    )

    const markerSource = new SourceVector();
    const circleStyle = new CircleStyle({radius: 10, stroke: new Stroke({color: 'red'}), fill: new Fill({color: 'rgb(240, 191, 191)'})});

    const features = markers.map((mark, idx) => {
        const point = new Point([mark.lon, mark.lat]);
        const feature = new Feature(point)
        feature.setStyle(new Style({
            image: circleStyle,
            stroke: new Stroke({ width: 10 }),
            text: new Text({ text: (idx + 1).toString(10), scale: 1, fill: new Fill({color: 'black'}) })
        }))
        return feature
    });

    markerSource.addFeatures(features);

    map.addLayer(new Vector({
        source: markerSource            
    }));   

}
