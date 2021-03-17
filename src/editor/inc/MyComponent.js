import React from "react";
import "./MyComponent.scss";

class MyComponent extends React.Component {
    render() {
        console.log("Hello World!zzz");
        console.log("hhhh");
        return <h1>Hello World</h1>;
        //return <h1>{this.props.title}</h1>;
        //return React.createElement('h1', null, `Title: ${this.props.title}`)
        //return <div className="x">{[1,2,3].map(item => <div>{item}</div>)}</div>
    }
}

export default MyComponent;
