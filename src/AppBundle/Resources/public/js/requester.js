var UrlBox = React.createClass({
    loadUrlsFromServer: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.home, status, err.toString());
            }.bind(this)
        });
    },
    
    handleUrlsSubmit: function(url) {
        var urls = this.state.data;
        var newUrls = url.urls.split("\n");

        for (var i = 0; i < newUrls.length; i++) {
            newUrls[i] = {
                'name': newUrls[i],
                'id': Date.now(),
                'status': -1
            };
        }
        var state = urls.concat(newUrls);

        this.setState({data: state});

        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: url,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    getInitialState: function() {
        return {data: []};
    },

    componentDidMount: function() {
        this.loadUrlsFromServer();
        setInterval(this.loadUrlsFromServer, this.props.interval);
    },

    render: function() {
        return (
            <div className="urlBox">
                <h1>Urls</h1>
                <UrlList data={this.state.data} />
                <UrlForm onUrlsSubmit={this.handleUrlsSubmit} />
            </div>
        );
    }
});

var Url = React.createClass({
    render: function() {
        var status = {
            '-1': 'Still working',
            '200': '200 OK',
            '301': 'Redirected',
            '404': 'Not found',
            '500': 'Server error',
        };

        return (
            <div className="url">
                <a href="#"> {this.props.data.name}</a>
                <p>Status: {status[this.props.data.status]}</p>
            </div>
        );
    }
});

var UrlList = React.createClass({
    render: function() {
        return (
            <div className="urlList">
                {this.props.data.map(function (url) {
                    return  <Url data={url}/>
                })}
            </div>
        );
    }
});

var UrlForm = React.createClass({

    getInitialState: function() {
        return {author: '', text: '', urls: ''};
    },

    handleSubmit: function(e) {
        e.preventDefault();

        var urls = this.state.urls;

        if (!urls) {
            return;
        }

        this.props.onUrlsSubmit({urls: urls});
        this.setState({urls: ''});
        this.clearTextArea(document.getElementById('urls'));
    },

    clearTextArea: function(e) {
        e.value = "";
    },

    handleUrlsChange: function(e) {
        this.setState({urls: e.target.value});
    },

    render: function() {
        return (
            <form className="urlForm" onSubmit={this.handleSubmit}>
                <textarea
                    name="urls"
                    id="urls"
                    cols="30"
                    rows="10"
                    onChange={this.handleUrlsChange}
                >
                </textarea>
                <input type="submit" value="Send requests" />
            </form>
        );
    }
});

ReactDOM.render(
    <UrlBox url="api/urls" interval={2000} />,
    document.getElementById('content')
);
