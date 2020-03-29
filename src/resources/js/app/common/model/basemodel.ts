import moment from "moment";

export abstract class BaseModel
{
    constructor(model?: {}) {
        this.fillModel(model ? model : {});
    } 
    
    abstract fillModel(_model: {}): {};
    abstract getArray(): {};
    
    setFieldValue(value: any, entry: any) {
        return (undefined === value) ? entry : value;
    }
    
    setDateValue(value: any, entry: any) {
        return (undefined === value) ? entry : moment(value);
    }
}