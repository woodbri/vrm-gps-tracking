import 'ol/ol.css';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer.js';
import Feature from 'ol/Feature';
import Map from 'ol/Map';
import OSM from 'ol/source/OSM';
import VectorSource from 'ol/source/Vector';
import Point from 'ol/geom/Point';
import WKT from 'ol/format/WKT';
import View from 'ol/View';
import {Circle, Fill, Stroke, Style, Text} from 'ol/style';
import {fromLonLat, toLonLat} from 'ol/proj';

const base = new TileLayer({
    type: 'base',
    visible: true,
    source: new OSM()
});

const format = new WKT();

const linestring = format.readFeature(gpsPath, {
  dataProjection: 'EPSG:4326',
  featureProjection: 'EPSG:3857',
});

const point = new Feature({
    geometry: new Point(fromLonLat([lon, lat]))
});

const fill = new Fill({
    color: 'rgba(255,51,51,0.2)'
});

const stroke = new Stroke({
    color: 'rgba(255,51,51,0.7)',
    width: 3
});

const styles = [
    new Style({
        image: new Circle({
            fill: fill,
            stroke: stroke,
            radius: 15
        }),
        fill: fill,
        stroke, stroke
    }),
    new Style({
        image: new Circle({
            fill: fill,
            stroke: stroke,
            radius: 15
        }),
        fill: fill,
        stroke, stroke
    })
];

const vector = new VectorLayer({
    source: new VectorSource({
        features: [linestring, point],
    }),
    style: styles
});


const map = new Map({
  layers: [base, vector],
  target: 'map',
  view: new View({
    center: fromLonLat([lon, lat]),
    zoom: 16,
  }),
});


